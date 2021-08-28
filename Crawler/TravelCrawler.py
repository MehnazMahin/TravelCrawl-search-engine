import praw
import json
import sys
from credentials import ID, SECRET, PASSWORD, AGENT, USERNAME
counter = 0

#reddit praw authentication
reddit = praw.Reddit(client_id=ID,
                     client_secret=SECRET,
                     user_agent=AGENT,
                     username=USERNAME,
                     password=PASSWORD)

#open the txt file that contains all subreddit name
with open(sys.argv[1], 'r') as infile:
    subreddits = infile.readlines()

subreddits = [subreddit.rstrip('\n') for subreddit in subreddits]

#define which objects to collect from a particular post
keyval = {
            'title':'title', 
            'selftext':'body',
            'id':'id',
            'url':'image',
            'num_comments':'num_comments',
            'permalink':'link',
            'score':'upvotes'
         } # list all key:value except for comments

#function to collect comments
def get_comments(submission):
    submission.comments.replace_more(limit=0)
    return [comment.body for comment in submission.comments.list()]

for subreddit in subreddits:
    data = []
    top = reddit.subreddit(subreddit).top(limit=int(sys.argv[2])) #change limits here, max = 999, collect TOP posts
    print('collecting subreddit: %s'%subreddit)
    for submission in top:
        counter+=1
        print('collecting post# %d' % counter)
        values = vars(submission)
        datum = {keyval[key]:values[key] for key in keyval.keys()} #collect objects of posts
        datum['comments'] = get_comments(submission)
        data.append(datum)
    with open('%s.json'%subreddit, 'a') as outfile:  # write to JSON file
        print('Writing to JSON file...')
        json.dump(data, outfile)







