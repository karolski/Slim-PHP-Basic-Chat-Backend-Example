
# Example chat application 

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/36c410d68bbce48c5e8b)

Documentation [here](https://documenter.getpostman.com/view/5824253/TVRka7xD)

This backend uses two entities: Message and User. It uses:
- SQLite database - in `var` folder (after setting up)
- JWT as authentication mechanism using RSA256
- Doctrine for ORM and DB access layer
- Follows Action-Service-Repository-Entity pattern
- PSR1 standard
- Static analysis with PHPStan and PHP_CodeSniffer


##### Configuration: `JWT_SECRET` in env, see .env for example 

For your convinience you can obtain all tokens (signed with development private key) at `/testing/get-jwt-tokens`
or by running `php commands/getTestJwtTokens.php` after setup. The tokens will work for 10 minutes, provided 
you will not change the default `JWT_SECRET` in `.env`



## One line run
```bash
docker-compose up
```
* in case you run it on a mac, you may need to replace `.env` with `.env.mac` for docker-compose whitespace errors

## Local setup

```bash
composer install
sh setup_db.sh
```

To run the application in development

```bash
composer start
```

### Test
```bash
composer test
composer test:coverage
composer analyse
composer sniff
composer sniff:fix
```
* you might need to install xdebug. For ubuntu `sudo apt install php-xdebug  `

### Disclaimers
- The application does not enforce https, so you can test it locally.
- the listing APIs don't use pagination - for the sake of simplicity. 
In case there would be long conversations or thousands of users,
it would be appropriate to use pagination.
______________________
©2020 Karol Skalski