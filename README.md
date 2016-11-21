Parses google drive spreadsheet and generates translation files
=======

#### about

this tool parses google drive spreadsheet into translation files - you can use single spreadsheet to generate
translation files in various formats that can be used in frontend, backend and mobile development. The markup of
the spreadsheet is described below, but nothing is as self explanatory as [an example](https://docs.google.com/spreadsheets/d/1AUAKxhuZyjYl4NdpQCLBcSZe2snKAOjcXArlHRIn_hM/edit?usp=sharing). 

#### config file

In the `\lang` directory of your project put a json file named `translate.json`. 

```
{
    "fileId" : "google drive file ID",
    "targets" : [
        {
            "format": "json",
            "pattern": "translations.%locale%",
            "sections": [
                "CORE",
                "FORMS"
            ]
        }
    ]
}
```
You can define multiple **targets** with the same or different format each. That way you can generate for example only
frontend translations or divide translations into multiple domain files. 

**Format** key is obligatory and can be one from the list of supported formats : 

`['json', 'xlf', 'android', 'iOS']`.

**Pattern** key is optional and is used as a resulting filename pattern (without file extension that is added 
automatically). If ommited defaults to naming convention of a target:

- for `json` : `%locale%` - example: `en.json`, `pl.json`
- for `xlf` : `messages.%locale%` - example: `messages.en.xlf`, `messages.pl.xlf`
- for `android`: `values-%locale%/strings` - example: `values-en/strings.xml`, `values-pl/strings.xml`
- for `iOS` : `%locale%.lproj/Localizable` - example: `en.lproj/Localizable.strings`, `pl.lproj/Localizable.strings`

**Sections** key is optional and define array of sections (root key levels in the translation spreadsheet) that are to 
be included in the resulting files. When ommited defaults to `_all`, meaning that all the keys will be included. 
 
The result files will be placed in the same directory as the `translate.json` configuration file. 

#### running the containter

`docker run -ti --rm -v <<lang directory>>:/lang tsurowiec/translations_from_gdrive`

The parser will ask you to go to the authorization link and paste the access code. Then the access token will be 
saved in the `lang directory`.

#### Google Spreadsheet markup:

Parser ignores every row until metadata row is found. 
Metadata row is first row that contains `>>>` marker. It defines first level of translation keys. Then multiple 
`>>>` follow to indicate next key levels. In that level you also define the various locales you want to put
in the translations spreadsheet.  

example : `>>>, >>>, >>>, en, fr, pl`

Every next row is parsed for translations. 
