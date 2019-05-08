#!/usr/bin/env python
# coding: utf-8

# In[1]:


import pandas
#load the dataset
dataset = pandas.read_csv('dataset1.txt', delimiter = "\n")
dataset.head()


# In[2]:


import re
import nltk
nltk.download('stopwords')
from nltk.corpus import stopwords
from nltk.stem.porter import PorterStemmer
from nltk.tokenize import RegexpTokenizer

nltk.download('wordnet')
from nltk.stem.wordnet import WordNetLemmatizer


# In[3]:


##Creating a list of stop words and adding custom stopwords
stop_words = set(stopwords.words("english"))


# In[4]:


performance = ["accurate", "consistent", "incomplete-data", "efficient workflows", "data", "data processing", "data transfers", "dedicated high-speed internet", "connectivity", "data import", "local", "system", "remote", "data size", "computation", "intensive", "faster", "standardized", "access", "execution time", "network", "data in motion", "analysis", "visualization"]
security = ["share information", "sensitive data", "maintains confidentiality", "secure storage", "standardized", "access level", "privileges", "authorized", "data value", "administrative error", "privacy", "passwords", "incorrect data", "data loss", "public", "private", "protected", "policies"]
corpus = []

for i in range(0, 12):
    #Remove punctuations
    #text = re.sub('[^a-zA-Z0-9]', ' ', dataset['Requests'][i])
    text = re.sub(' ', ' ',dataset['Requests'][i])
    #Convert to lowercase
    text = text.lower()
    
    #remove tags
    text=re.sub("&lt;/?.*?&gt;"," &lt;&gt; ",text)
    
    #remove special characters and digits
    #text=re.sub("(\\W)+"," ",text)
    
    ##Convert to list from string
    text = text.split()
    
    ##Stemming
    ps=PorterStemmer()
    #Lemmatisation
    lem = WordNetLemmatizer()
    text = [lem.lemmatize(word) for word in text if not word in  
            stop_words] 
    text = " ".join(text)
    corpus.insert(0, text)

    #view Corpus item
    #corpus[i]
    #corpus[]
    
    print(corpus[0] + "\n")
    
    
    from sklearn.feature_extraction.text import CountVectorizer
    import re
    cv=CountVectorizer(max_df=1.1,stop_words=stop_words, max_features=10000, ngram_range=(1,1))
    X=cv.fit_transform(corpus)
    
    print(list(cv.vocabulary_.keys())[:100])
   
    
    
    from sklearn.feature_extraction.text import TfidfTransformer
 
    tfidf_transformer=TfidfTransformer(smooth_idf=True,use_idf=True)
    tfidf_transformer.fit(X)
    #get feature names
    feature_names=cv.get_feature_names()
 
    #fetch document for which keywords needs to be extracted
    doc=corpus[0]
 
    #generate tf-idf for the given document
    tf_idf_vector=tfidf_transformer.transform(cv.transform([doc]))
    

        #Function for sorting tf_idf in descending order
    from scipy.sparse import coo_matrix
    def sort_coo(coo_matrix):
        tuples = zip(coo_matrix.col, coo_matrix.data)
        return sorted(tuples, key=lambda x: (x[1], x[0]), reverse=True)

    def extract_topn_from_vector(feature_names, sorted_items, topn=10):
        """get the feature names and tf-idf score of top n items"""

        #use only topn items from vector
        sorted_items = sorted_items[:topn]

        score_vals = []
        feature_vals = []

        #word index and corresponding tf-idf score
        for idx, score in sorted_items:

            #keep track of feature name and its corresponding score
            score_vals.append(round(score, 3))
            feature_vals.append(feature_names[idx])

        #create a tuples of feature,score
        #results = zip(feature_vals,score_vals)
        results= {}
        for idx in range(len(feature_vals)):
            results[feature_vals[idx]]=score_vals[idx]

        return results
    #sort the tf-idf vectors by descending order of scores
    sorted_items=sort_coo(tf_idf_vector.tocoo())
    #extract only the top n; n here is 10
    keywords=extract_topn_from_vector(feature_names,sorted_items,5)

    #now print the results
    #print("\nAbstract:")
    #print(doc)
    #print("\nKeywords:")
    #for k in keywords:
        #print(k,keywords[k])
        
        
    #performance check
    for z in performance:
        if z in feature_names:
            print(z + " is performance")
            
    print("\n")
    
    #security check
    for m in security:
        if m in feature_names:
            print(m + " is security")
            
    
    print("\n"+"\n"+"\n")
    
    corpus.pop(0)
    


# In[ ]:
