# immersion

## What is Immersion?
Immersion is a plugin for [Nozomi](https://github.com/afroraydude/nozomi-core) for creation of study guides and educational mini-sites within a Nozomi install.

## Install
Manual install is similar to Nozomi's. Just add the repository to your `composer.json` file.

```json
    "repositories": [{
        "type": "vcs",
        "url": "https://github.com/afroraydude/nozomi-core"
    },
    {
        "type": "vcs",
        "url": "https://github.com/afroraydude/immersion"
    }],
    "require": {
        "afroraydude/nozomi-core": "dev-master",
        "afroraydude/immersion": "dev-master",
        ...
    }
```

Then run:

```sh
composer update
```
