Parses google drive spreadsheet and generates json translation files
=======


in the `\lang` directory of your project put a json file named `translate.json`

```
{
    "fileId" : "google drive file ID"
}
```
run the containter

`docker run -ti --rm -v <<lang directory>>:/lang tsurowiec/translations_from_gdrive`

