package com.mishkat;

import org.apache.lucene.analysis.Analyzer;
import org.apache.lucene.analysis.standard.StandardAnalyzer;
import org.apache.lucene.document.*;
import org.apache.lucene.index.DirectoryReader;
import org.apache.lucene.index.IndexReader;
import org.apache.lucene.index.IndexWriter;
import org.apache.lucene.index.IndexWriterConfig;
import org.apache.lucene.queryparser.classic.MultiFieldQueryParser;
import org.apache.lucene.queryparser.classic.QueryParser;
import org.apache.lucene.search.IndexSearcher;
import org.apache.lucene.search.Query;
import org.apache.lucene.search.ScoreDoc;
import org.apache.lucene.search.TopDocs;
import org.apache.lucene.store.Directory;
import org.apache.lucene.store.FSDirectory;
import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;

import java.io.File;
import java.io.FileReader;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

public class LuceneIndexing {
    /*
    private static int createIndex(String fileDirectoryPath, String indexDirectoryPath, int indexedPostCount) throws IOException, ParseException {
        Directory indexDirectory = FSDirectory.open(new File(indexDirectoryPath).toPath());
        Analyzer analyzer = new StandardAnalyzer();

        IndexWriterConfig writerConfig = new IndexWriterConfig(analyzer);
        writerConfig.setOpenMode(IndexWriterConfig.OpenMode.CREATE);
        IndexWriter indexWriter = new IndexWriter(indexDirectory, writerConfig);

        File[] files = new File(fileDirectoryPath).listFiles();
        int actualIndexedPosts = 0;
        if(files!=null) {
            for (File file : files) {
                int indexed = indexPostsAsDocuments(indexedPostCount, indexWriter, file);
                actualIndexedPosts += indexed;
                if(indexed == indexedPostCount)
                    break;
                indexedPostCount -= indexed;
            }
            indexWriter.close();
        }
        return actualIndexedPosts;
    }

    private static int indexPostsAsDocuments(int indexedPostCount, IndexWriter writer, File file) throws IOException, ParseException {
        int indexedPosts = 0;

        //Parsing the input JSON files and adding posts as documents
        JSONParser jsonParser = new JSONParser();
        Object object = jsonParser.parse(new FileReader(file));
        JSONArray allPosts = (JSONArray) object;
        indexedPosts = (indexedPostCount < allPosts.size())? indexedPostCount:allPosts.size();
        Iterator itr = allPosts.iterator();
        for(int i=0; i<indexedPosts; i++){
            JSONObject postObject = (JSONObject) itr.next();
            Document document = new Document();

            String title = (String) postObject.get("title");
            String body = (String) postObject.get("body");
            String id = (String) postObject.get("id");
            String image = (String) postObject.get("image");
            long numComments = (long) postObject.get("num_comments");
            String link = (String) postObject.get("link");
            long up_votes = (long) postObject.get("upvotes");

            StringBuilder comments = new StringBuilder();
            JSONArray comment_array = (JSONArray) postObject.get("comments");
            Iterator it2 = comment_array.iterator();
            while(it2.hasNext()){
                comments.append(it2.next());
            }

            document.add(new TextField("title", title, Field.Store.YES));
            document.add(new TextField("body", body, Field.Store.YES));
            document.add(new StringField("id", id, Field.Store.YES));
            document.add(new StringField("image", image, Field.Store.NO));
            document.add(new StoredField("num_comments", numComments));
            document.add(new StringField("link", link, Field.Store.YES));
            document.add(new StoredField("upvotes", up_votes));
            document.add(new TextField("comments", comments.toString(), Field.Store.NO));
            writer.addDocument(document);
        }
        return indexedPosts;

    }*/

    private static List<String> searchIndex(String indexDirectoryPath, String searchQuery) throws IOException, org.apache.lucene.queryparser.classic.ParseException {
        Directory indexDirectory = FSDirectory.open(new File(indexDirectoryPath).toPath());
        IndexReader indexReader = DirectoryReader.open(indexDirectory);
        IndexSearcher indexSearcher = new IndexSearcher(indexReader);
        StandardAnalyzer analyzer = new StandardAnalyzer();

        String[] fields = {"title", "comments"};
        QueryParser queryParser = new MultiFieldQueryParser(fields, analyzer);
        Query query = queryParser.parse(searchQuery);
        //long startTime = System.currentTimeMillis();
        int maxToRetrieve = 200;
        int topHitCount = 10;
        TopDocs queryResults = indexSearcher.search(query, maxToRetrieve);
        //long endTime = System.currentTimeMillis();
        //System.out.println("Results found: "+ queryResults.totalHits + ", Search time: "+(endTime-startTime) +" ms");
        ScoreDoc[] hits = queryResults.scoreDocs;
        List<String> tops = new ArrayList<>();

        // Iterate through the results:
        int count = 0;
        for (int rank = 0; rank < hits.length; ++rank) {
            Document document = indexSearcher.doc(hits[rank].doc);
            if(tops.size() < topHitCount){
                String snippetbody = "<Not applicable>";
                String body = document.get("body");
                if (body.length() > 3) {
                    snippetbody = body;
                }
                tops.add((rank + 1) + " (score:" + hits[rank].score + ")--> " + document.get("title")+" -- "+document.get("link") + " |" + snippetbody + "");
            }
            else {
                break;
            }
        }

        indexReader.close();

        return tops;
    }

    public static void main(String[] args) throws IOException, ParseException, org.apache.lucene.queryparser.classic.ParseException {
        String pwd = args[0];//record present directory, where all files are
        String queryString = args[1]; //record user-supplied query
        String indexDirectoryPath = pwd + "/index";
        List<String> tops = searchIndex(indexDirectoryPath, queryString);

        for(String top : tops) {
            System.out.println(top);
        }
    }
}
