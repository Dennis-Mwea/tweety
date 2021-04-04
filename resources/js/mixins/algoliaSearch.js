import algoliasearch from "algoliasearch/lite";
import AppInfiniteHits from "@/components/InfiniteHits";
import {AisConfigure, AisInstantSearch, AisSearchBox} from "vue-instantsearch";

export default {
    components: {
        AppInfiniteHits,
        AisInstantSearch,
        AisSearchBox,
        AisConfigure
    },

    filters: {
        getAvatar: path => {
            return "/" + path.substr(17, path.length);
        }
    },

    data() {
        const algoliaClient = algoliasearch(
            'AXHNZOFH4V',
            '7d23a9f8ca2a0126273b59f1d7d34944'
        );
        const searchClient = {
            search(requests) {
                if (requests.every(({params}) => !params.query)) {
                    return Promise.resolve({
                        results: requests.map(() => ({
                            hits: [],
                            nbHits: 0,
                            nbPages: 0,
                            processingTimeMS: 0
                        }))
                    });
                }

                return algoliaClient.search(requests);
            }
        };

        return {
            searchClient,
            indexName: "tweets",
            showTweetHits: true,
            showUserHits: false
        };
    },

    methods: {
        searchTweets() {
            this.updateIndex("tweets");
            this.showTweetHits = true;
            this.showUserHits = false;
        },

        searchUsers() {
            this.updateIndex("users");
            this.showUserHits = true;
            this.showTweetHits = false;
        },

        updateIndex(index) {
            this.indexName = index;
        }
    }
};
