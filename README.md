## Task

We created have a small API with two endpoints:

`POST /hugs`, and `/huggg/get/{id}`

However, after a review, we have determined that these are high in
technical debt - your task is to refactor the code in the HugController
as you see fit.

There are no right or wrong answers, we are just interested to see how
you approach the task. Nothing is off limits, change / add / delete
anything you need, the only constraint is that existing functionality
should not be affected.

Estimated completion time is around one hour, but please do not spend
more than 2.

## Submission

We are happy for you to return this to us however you find easiest,
either email us a link to your copy of the repository, or zip it up and
email it back to us.

Please note: part of the test is your usage of git, so it is important
that, if you choose to zip it to return, you include the git history.

## Requirements

 - Docker

## Running the tests

- `docker build -t huggg-tech-test .`
- `docker run huggg-tech-test`
