You can change Okta login params in ```config.php``` file

```app_url``` - current app domain (example http://your-domain)<br/>
```client_id``` - Okta client ID<br/>
```client_secret``` - Okta client secret<br/>
```org_url``` - Okta Org URL

Also you need to add link ```http://your-domain/pages/login-callback.php``` to your Okta application as Login redirect URI