A little wrapper around selenium web driver to make it a bit nicer to use.

See the file src/example.php for usage

Run with:

php /data/bin/console.php carnage-selenium:run-tests --test-suite ./src/hirepower.php --selenium-host http://localhost:4444/wd/hub --base-url http://localhost

For convenience, I have included a docker-compose file which will spin up a selenium grid
