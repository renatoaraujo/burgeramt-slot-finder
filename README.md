Bürgeramt Slot Finder
===

The easiest way to run this application is if you have Docker installed and running on your machine.

## Configuration

Create your `.env.local` from `.env.dist` with: 
 - Your AWS credentials (keep in mind you will need at least AWS::AmazonSNSFullAccess policy)
 - Your email (optional for now because it is not sending email yet)
 - Your phone number (+49XXXXXX)

### Setup the application
```bash
$ make setup
```

### Check if there is any Anmeldung einer Wohnung available
```bash
$ make find-anmeldung-slots
```

The application does not keep running on your machine, if you want to, you must configure a cronjob.

Ex: running every 30 minutes.
```bash
0,30 * * * * path/to/project bin/console app:find-anmeldung-slots 
```

This application is for fun purposes and to create something that can be helpful for new comers to Berlin, if you have any new idea or wants to contribute please feel free to do so.
You can find something else (and simple) at https://gist.github.com/mugli/f538e8fb0554267c1028068b75e17c59
  
Enjoy :beers:
