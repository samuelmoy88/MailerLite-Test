# MailerLite-Test
Welcome to my MailerLite test!

## API:
  The JSON API is located in the api.mailerlite.localhost folder. To launch it just open the CLI, cd into it and run `composer install`. 
  The DB is located in the root folder and the credentials are in /Core/config.php
  
## App/Frontend:
  The App is already compiled in app.mailerlite.localhost/dist folder, the API calls are hardcoded to 'http://api.mailerlite.localhost'.
  If you wish to change something, just run `npn install` first 
  
## The routes go as following:
  ## Subscribers:
      GET `/subscribers` will fetch the list of Subscribers\
      GET `/subscribers/:id` will fetch a given Subscriber\
      POST `/subscribers` will create a new Subscriber\
      PUT `/subscribers/:id` will update a given Subscriber\
      DELETE `/subscribers/:id` will delete a given Subscriber\
    
  ## Fields:
    GET `/fields` will fetch the list of Fields\
    GET `/fields/:id` will fetch a given Field\
    POST `/fields` will create a new Field\
    PUT `/fields/:id` will update a given Field\
    DELETE `/fields/:id` will delete a given Field\
    
 ## Subscriber fields:
    GET `/subscribers/:subscriber/fields` will fetch a given Subscriber field\
    POST `/subscribers/:subscriber/fields` will create a new Subscriber field\
    PUT `/subscribers/:subscriber/fields/:id` will update a given Subscriber field\
    DELETE `/subscribers/:subscriber/fields/:id` will delete a given Subscriber field\
  
The package used to validate the email addresses is using 'info@mailerlite.com' as default to the be provided to the API.

Thanks again for the opportunity!
