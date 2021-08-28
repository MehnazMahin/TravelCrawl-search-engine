# Instructions to deploy the crawler:
1. In “Crawler/credentials.py” file, write the authentication credentials required (client ID, client secret, app name, reddit username, reddit password).
2. In ‘Crawler/subreddits.txt’ file, write the names of subreddits you want to crawl (Travel, solotravel etc) in new lines.
3. Run the crawler using the following command:
```
path/to/crawler.sh <input-file.txt> <number_of_posts>
```
For example,
```
cd Crawler
./crawler.sh subreddits.txt 999
```

# Instructions to build the Lucene index:
```
java –jar path/to/LuceneIndexing.jar <Input directory> <Index directory>
```
For example,
```
cd Lucene-MapReduce-Indexes
java –jar LuceneIndexing.jar data index
```

# Instructions to build and store the MapReduce Index:
1. Build the MapReduce index in Hadoop:
```
cd Lucene-MapReduce-Indexes
hadoop jar MapReduceIndexing-1.0-SNAPSHOT.jar MapReduceIndexing
```
It will upload "data/" and create "mr_index/" in HDFS. Copy "mr_index/" from HDFS to the working local directory.
2. Create the JSON files of the created MapReduce index:
```
java -jar MongoDBJsonFormat-1.0-SNAPSHOT.jar MongoDBJsonObject
```
It will create "MongoDBDirectory/" with the required JSON files for MongoDB. Copy the files of "/MongoDBDirectory" to "../mr_index/MongoDBDirectory/".
3. Install MongoDB on your local machine and create a database named “IR” and two collections named “Index” and “Dataset”.
4. Import the JSON files to MongoDB: Go to “../mr_index/MongoDBDirectory/” and run “myscript.sh”. This will import our index data into mongo. In similar way import original dataset to “Dataset” collection.

# Instructions to build the search engine:
1. Go to “mr_index/irs.py” and change Line # 2 and Line# 3. Give the path to python site packages of your local PC.
2. Go to “lucene_index/” and edit the “indexer.sh” file. Uncomment the “mvn” commands.
3. Go to the root directory of the code folder. Open your terminal and start “apache” and “php” server.
If you are using Linux type:
```
sudo apachectl start
sudo php -S localhost:8080
```
If you are using Mac, then just run “php -S localhost:8080”. Server will start at “https://localhost:8080”