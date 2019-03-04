<?php

namespace App\Api\Controllers;

use App\Brand;
use App\Charge;
use App\Price;
use App\Hug;
use App\User;
use App\Message;
use App\Redemption;
use App\Participant;
use App\Transaction;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\BrandRepository;
use Nathanmac\Utilities\Parser\Parser;
use App\Api\Requests\HugRequest;
use Carbon\Carbon;
use App\Events\HugggSent;
use App\Utilities\LocationManager;
use App\Utilities\GoldenHuggg;
use App\Utilities\EmbeddedEntityProcessor;
use App\Exceptions\PaymentException;

use \Symfony\Component\HttpKernel\Exception\BadRequestHttpException as BadRequest;
use Lcobucci\JWT\Parser as Parse;

/**
 * @Resource("Hugs", uri="/hug")
 */
class HugController extends BaseController
{

    private $eagleEye;
    private $integrationType = [
        'eagleEye' => 1,
        'unintegrated' => 2,
        'eagleEyeQR' => 3,
        'voucher' => 4,
    ];

    public function __construct(BrandRepository $brands)
    {
    }

    /**
     * Gets all single off
     *
     * Returns details of offer added to merchant
     *
     * @Get("/")
     * @Request(headers={"Authentication": "Bearer: jsonwebtokenstring"})
     */
    public function show(Request $request, $id)
    {
        if (strlen($id) > 15) {
            $huggg = Hug::with([
                'purchase.brand.locations',
                'message.conversation',
                'sender',
            ])->where(['id' => $id])->first()->toArray();
        } else {
            $huggg = Hug::with([
                'purchase.brand.locations',
                'message.conversation',
                'sender',
            ])->where(['shortcode' => $id])->first()->toArray();
        }

        if ($huggg['purchase']['brand']['consolidated'] === 1) {
            $huggg['purchase']['brand']['locations'] =
                Brand::whereHas(
                    'consolidated_prices',
                    function($query) use ($huggg) {
                        $query->where('id', '=', $huggg['purchase_id']);
                    }
                )
                ->with('locations')
                ->get()
                ->pluck('locations')
                ->each(function($store) {
                    $store->makeHidden(['brand']);
                })
                ->flatten()
                ->toArray();

            foreach($huggg['purchase']['brand']['locations'] as &$location) {
                $location['brand_id'] = $huggg['purchase']['brand']['id'];
            }
        }

        $response = ['data' => $huggg];

        // OLF-372 - weird hack to make this endpoint BC for Bloody
        // Mary's way of grabbing data
        $ids = [];
        foreach ($response['data']['purchase']['brand']['locations'] as $store) {
            $ids[] = $store['id'];
        }
        $response['data']['purchase']['brand']['stores'] = $ids;

        $response['hug'] = $response['data'];  // unfortunate duplication for legacy apps

        return $response;
    }

    protected function getReceiver($number)
    {
        if (!$number) {
            return NULL;
        }
        $e164 = phone_format($number, 'GB', \libphonenumber\PhoneNumberFormat::E164);
        $receiver = User::where('phone_number', $e164)->first();
        return $receiver ? $receiver : User::create([
            'name' => '',
            'phone_number' => $e164,
            'active' => 0,
            'type' => 3
        ]);
    }

    private function createHugggFor(Price $price, User $user, array $tags = [])
    {
        if ($price->huggg_tag) {
            $tags[] = $price->huggg_tag;
        }

        $huggg = Hug::create([
            'status' => 5,
            'tags' => '|' . implode('|', $tags) . '|',
        ]);
        $huggg->save();

        $price->soldItems()->save($huggg);
        $user->sentHugs()->save($huggg);
        $huggg->setExpiryDate();

        return $huggg;
    }

    private function addMessage(Hug $huggg, User $sender, string $text)
    {
        $message = new Message(['message' => $text]);
        $message->sender()->associate($sender)->save();
        $message->hug()->associate($huggg)->save();
        return $message;
    }

    /**
     * Stores a new Hug
     *
     * Creates a huggg in the system, after payment has been taken
     *
     * @Post("/")
     * @Request({"card" : "cardtokenstring", "receiver": "+447888888888", "purchase" : 123,
     *  "message" : "Message copy"
     * }, headers={"Authentication": "Bearer: jsonwebtokenstring"})
     */
    public function store(HugRequest $request)
    {
        $text = $request->get('message');
        $user = $request->user();

        $receiver = $this->getReceiver($request->get('receiver'));
        $price = Price::where('id', $request->get('purchase'))->firstOrFail();

        \DB::beginTransaction();

        $huggg = $this->createHugggFor($price, $user, $request->get('tags', []));

        if ($receiver) {
            $receiver->recievedHugs()->save($huggg);
        }

        $message = $text ? $this->addMessage($huggg, $user, $text) : null;

        $huggg->save();

        \DB::commit();

        event(new HugggSent($user, $huggg, $receiver, $text));

        return response()->json(['success' => true, 'hug' => $huggg]);
    }
}
