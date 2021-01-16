# Hey Burrito

### The FREE Hey Taco Slack Bot replacement

## Setup

### Slack Events API Challenge

Read this documentation [Events API Request URLs](https://api.slack.com/apis/connections/events-api#url_verification)

- You must have an active SSL certificate on your URL endpoint
- The URL verification only needs to be done when setting up or changing the URL endpoint

The way the JSON payload from Slack looks like this:

```json
{
    "type": "url_verification",
    "token": "hwLi3kEqs0cVt5nkMwHheL9f",
    "challenge": "j26kOzF3Xk1mrwPqijjoMo96Q86iLgESYzOdAhTMUWaaz3iu7Lok"
}
```

_Slack may trip you up by showing you what their payload looks like if you fail too many times. The payload data that 
they show you is wrong. The payload data that they show you has the data in an object called body. Luckily the function
I have created accounts for this in case Slack ever does decide to wrap the data in an object called body._

To perform the URL verfication follow these steps:

1. In `routes/api.php` change the line for the `/burrito` endpoint to have it use the `slackChallenge()` method instead
of the `giveBurrito()` method.
   
   i.e. It should look like this `Route::post('/burrito', [BurritoController::class, 'slackChallenge']);`
2. In the Slack Bot Event Subscriptions page enter the url endpoint you where that method will now be hit.
    
    i.e. `https://{yourapiurl}/api/burrito`

    1. If your verification fails please reread the documentation and run the tests to see if 
the function is operating correctly.
3. Subscribe to the following bot events: `app_mention` and `message.channels`
4. Click _'Save Changes'_
5. Revert back your `routes/api.php` file.
