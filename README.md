Bürgeramt Slot Finder
===

The easiest way to run this application is if you have Docker installed and running on your machine.

## Configuration

Create your `.env.local` from `.env.dist` with: 
  - AWS_KEY=<YOUR_AWS_KEY>
  - AWS_SECRET=<YOUR_AWS_SECRET>
  - AWS_SNS_TOPIC=<YOUR_SNS_TOPIC>
    - Eg: arn:aws:sns:eu-central-1:<123456678>:<TOPIC_NAME>


:bulb: For your AWS credentials you will need at least AWS::AmazonSNSFullAccess policy
  
### It works with IFTTT

If you have IFTTT and wants to blink your lights or maybe play music at your home when new spots are available,
just create a WebHook at https://ifttt.com/maker_webhooks and change your environment variables to:
  - IFTTT_WEBHOOK_ENABLED=1
  - WEBHOOK_KEY=<YOUR_KEY>
  - WEBHOOK_EVENT_NAME=<YOUR_EVENT_NAME>

### Setup the application
```bash
$ make setup
```

### Check if there is any Anmeldung einer Wohnung available
```bash
$ make run
```

The application runs the command every 5 minutes, if you want to change that just change the env variable `WAIT_TIME` to your desired time (seconds) at `.env.local`.

### Background

If you want to keep it running from a server overnight or days just use the script `bin/run` with the following command:
* Run script does not use Docker, if you want to run it on Docker please change the script before execution.

```bash
$ nohup /app/bin/run > /app/var/log/run.log 2>&1 & echo $! > /app/var/save_pid.txt
``` 

This application is for fun purposes and to create something that can be helpful for new comers to Berlin, if you have any new idea or wants to contribute please feel free to do so.
You can find something else (and simple) at https://gist.github.com/mugli/f538e8fb0554267c1028068b75e17c59
  
Enjoy :beers:
