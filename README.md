# MeiliSearch Moodle

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

Welcome  This tool simplifies the search in moodle database.
Meilisearch is a RESTful search API in moodle. It aims to be a ready-to-go solution for everyone who wants a fast and relevant search experience for their end-users ⚡️🔎
## Features

- **Search as you type:** Results are updated on each keystroke using prefix-search Answers in less than 50 milliseconds.

- **Typo tolerance:** Get relevant matches even when queries contain typos and misspellings
- **Returns the whole document:** The entire document is returned upon search
- **Highly customizable search and indexing:** Customize search behavior to better meet your needs

  - _Custom ranking:_ Customize the relevancy of the search engine and the ranking of the search results
  - _Filtering and faceted search:_ Enhance user search experience with custom filters and build a faceted search interface in a few lines of code
  - _Highlighting:_ Highlighted search results in documents by **`const MARKER_OPEN='<span class="highlight-search">';`**
  - _Stop words:_ Ignore common non-relevant words like of or the
  - _Synonyms:_ Configure synonyms to include more relevant content in your search results

- **RESTful API:** Integrate Meilisearch in your technical stack with our plugins
- ### **Swagger Url:** **`/api/docs`**
- **Quick and easy changes:** you can simply change anything on your demand [in trait files](#develop)
` Traits >> Model >> {IndexName}GetSearchAttribute.php `
- **API key management:** Protect your instance with API keys. Set expiration dates and control access to indexes and endpoints so that your data is always safe
- **Multi-tenancy and tenant tokens:** Manage complex multi-user applications. Tenant tokens help you decide which documents each one of your users can search
- **Multi-search:** Perform multiple search queries on multiple indexes with a single HTTP request


## Getting Started

1. Clone this repository:

```bash

```

2. Install the required dependencies in dockerfile:

```dockerfile

  # Meilisearch
  search:
    image: getmeili/meilisearch:v1.4
    # container_name: mdl-meilisearch
    restart: always
    ports:
      - '7700:7700'
    volumes:
      - ./meili_data:/meili_data
    networks:
      - app-net
    healthcheck:
      test:
          - CMD
          - wget
          - '--no-verbose'
          - '--spider'
          - 'http://localhost:7700/health'
      retries: 3
      timeout: 5s

  php:
    image: hub.azadiweb.ir/php:8.2-fpm
    restart: always
    volumes:
      - ./opcache-off.ini:/usr/local/etc/php/conf.d/opcache.ini
      - ./www/:/var/www/html/
      - /home/zibafar/mau/exam/:/var/www/exam/
      - /home/zibafar/mau/exam/storage/mdlfile/:/var/www/html/moodle_data/filedir/
      - /home/zibafar/mau/searcher/:/var/www/searcher/
      - /home/zibafar/mau/joinin/:/var/www/joinin/
      - /home/zibafar/mau/phpMyAdmin/:/var/www/phpMyAdmin/
    command: ['bash', '-c', 'php-fpm']
    healthcheck:
      test: ['CMD', 'php', '-v']
      interval: 15s
      timeout: 5s
      retries: 6
    networks:
      - app-net
    logging:
      <<: *logging
```

3. Set up your OpenAI API key by exporting it as an environment variable:

```bash
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=172.17.0.1:7700
```
4 . create a "configs table" in database for con job task
```bash
php artisan migrate
```
## Usage

Run the following command to import data to Meilisearch AND primary setup :

```bash
 php -d "disable_functions=" artisan 
                      meilisearch:setup
                      {--domain=}
```
## CronJob

Set cornJob for daily update of search engine and sync data
```bash
1 1 * * *  cd  /var/www/meilisearch && /usr/local/bin/php  -d "disable_functions=" artisan sync:records >>  /var/log/cron/`date +\%Y\%m\%d`.log 2>&1
```


## Meilisearch Address
Default address is: 
**`http://127.0.0.1:7700/`**

## Indexes
 -  course 
   - دروس 
 -  forum
    -  تالارگفتمان
 -  video
     -  ویدئو
 -  assign
     -  تکالیف
 -  quiz
   - آزمون 
 -  other
     -  page and url  and ...
## Commands<a id='Commands'></a>

If the logic of the program changes in terms of sorting type or meilisearch filters during the development, this command should be executed

### **Filters**

```bash
 php -d "disable_functions=" artisan
         scout:filters
        {index : The index you want to work with.}
        {--domain=}
```
### **Sort**

```bash
 php -d "disable_functions=" artisan 
        scout:sorts
        {index : The index you want to work with.}
        {--domain=}
```
### **FLush : remove data from Meilisearch**
```bash
 php -d "disable_functions=" artisan 
        meilisearch:flush 
        {index : The index you want to work with.}
        {--domain=}
```

### Sync data : 
```bash
 php -d "disable_functions=" artisan 
        sync:records
        {index : The index you want to work with.}
        {--domain=}
```
## develop<a id='develop'></a>: 
**Quick and easy customize:** you can simply change anything on your demand in trait files
`Traits >> Model >> {IndexName}GetSearchAttribute.php`

```php
1. toSearchableArray(): mixed[]
2. getSearchFilterAttributes(): string[]
3. getSearchSortAttributes(): string[]
4. getSearchHighlightAttributes(): string[]
5. getSearchCropAttributes(): string[]
6. getSearchRetrieveAttributes(): string[]
7. getStopWords(): array
8. prepareFilters(filters): string[]
9. prepareSort(sort): string[]
```
execute [commands](#Commands) after any changes in above functions it is necessary
