Parses google drive spreadsheet and generates translation files
=======


#### preparations

in the `\lang` directory of your project put a json file named `translate.json`. 

```
{
    "fileId" : "google drive file ID",
    "targets" : [
        "json",
        "xlf"
    ]
}
```
if you only want one of the formats, you can remove it from 
the `targets` array. 

The result files will be
placed in the same directory.

#### running the containter

`docker run -ti --rm -v <<lang directory>>:/lang tsurowiec/translations_from_gdrive`

The parser will ask you to go to the authorization link and paste the access code. Then the access token will be 
saved in the `lang directory`.

#### Google Spreadsheet markup:

Parser ignores every row until metadata row is found. 
Metadata row starts with `###` (metadata column), then multiple `>>>` follow (key levels), finally the locales that 
are being translated.

example : `###, >>>, >>>, >>>, en, fr, pl`

Every next row is parsed for translations. 

You can set which rows are going to witch format by putting `j` or `x` mark in metadata column.
Rows without mark go to every format generated.
