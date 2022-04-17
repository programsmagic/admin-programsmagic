import Vue from "vue";
import Vuex from "vuex";
import createPersistedState from "vuex-persistedstate";
import Gate from "./Gate";

Vue.use(Vuex);

// Modules
// import Posts from "./store/posts";

const store = new Vuex.Store({
    plugins: [createPersistedState()],
    state: {
        posts: [],
        categories: [],
        comments: null,
        tags: [],
        postViews: [],
        mostReadedPosts: [],
        recentPosts: [],
        featuredPosts: [],
        postsByCategory: [],
        mostViewedPosts: [],
        searchResult:"",
        ad:"",
        top2Post:[],

    },

    mutations: {
        setPosts(state, payload) {
            state.posts = payload;
        },
        setCategories(state, payload) {
            state.categories = payload;
        },
        setTags(state, payload) {
            state.tags = payload;
        },
        SET_MOST_READED(state, payload) {
            state.mostReadedPosts = payload;
        },
        SET_RECENT_POSTS(state, payload) {
            state.recentPosts = payload;
        },
        SET_FEATURED_POSTS(state, payload) {
            state.featuredPosts = payload;
        },
        SET_POSTS_BY_CATEGORY(state, payload) {
            state.postsByCategory = payload;
        },
        SET_MOST_VIEWED_POSTS(state, payload) {
            state.mostViewedPosts = payload;
        },
        SET_SEARCH_RESULT(state, payload) {
            state.searchResult = payload;
        },
        SET_AD(state, payload) {
            state.ad = payload;
        },
        SET_TOP_POST(state, payload) {
            state.top2Post = payload;
        },
        SET_COMMENTS(state, payload) {
            state.comments = payload;
        }
    },

    actions: {
        async getPosts({commit}) {
            let response;
            if (this.$gate.isAuthorOrAdmin())
                axios.get('api/posts').then(({data}) => (
                    commit('setPosts',data),
                    response = data
                ));
            return response;
        },
        // async reloadCategories({commit}) {
        //     const response = await BlogServices.getCategories();
        //     commit('setCategories', response.data);
        //     return response.data;
        // },
        // async reloadTags({commit}) {
        //     const response = await BlogServices.getTags();
        //     commit('setTags', response.data);
        //     return response.data;
        // },
        //
        // async getMostReadedPosts({commit}) {
        //     const response = await BlogServices.getMostReadedPosts();
        //     commit('SET_MOST_READED', response.data)
        //     return response.data;
        // },
        // async getRecentPosts({commit}) {
        //     const response = await BlogServices.getRecentPosts();
        //     commit('SET_RECENT_POSTS', response.data)
        //     return response.data;
        // },
        // async getFeaturedPosts({commit}) {
        //     const response = await BlogServices.getFeaturedPosts();
        //     commit('SET_FEATURED_POSTS', response.data)
        //     return response.data;
        // },
        // async getPostsByCategory({commit}, id) {
        //     const response = await BlogServices.getPostsByCategory(id);
        //     commit('SET_POSTS_BY_CATEGORY', response.data)
        //     return response.data;
        // },
        // async getMostViewedPosts({commit}) {
        //     const response = await BlogServices.getPostByViews();
        //     commit('SET_MOST_VIEWED_POSTS', response.data)
        //     return response.data;
        // },
        // async getSearchResult({commit},payload) {
        //     const response = await BlogServices.search(payload);
        //     commit('SET_SEARCH_RESULT', response.data.data)
        //     return response.data.data;
        // },
        // async getAd({commit}) {
        //     const response = await BlogServices.getRadomeAd();
        //     commit('SET_AD', response.data)
        //     return response.data;
        // },
        // async getTopPost({commit}) {
        //     const response = await BlogServices.getTop2Post();
        //     commit('SET_TOP_POST', response.data)
        //     return response.data;
        // },
        async setComments({commit}){
            let response;
                await axios.get('api/getAllComments').then(({data}) => (
                    commit('SET_COMMENTS', data),
                        response = data
                ));
            return response;
        }
    },

    getters: {
        getTags(state) {
            return state.tags;
        },
        getAllPosts(state) {
            return state.posts;
        },
        getCategories(state) {
            return state.categories;
        },
        getMostReadPosts: state => state.mostReadedPosts,
        getComments: state => state.comments,
        getRecentPosts: state => state.recentPosts,
        getFeaturedPosts: state => state.featuredPosts,
        getPostsByCategory: state => state.postsByCategory,
        getMostViewedPosts: state => state.mostViewedPosts,
        getSearchResult: state => state.searchResult,
        getAd: state => state.ad,
        getTopPosts: state => state.top2Post,
        getRecentPosts2(state) {
            let data = state.posts;
            data = data.reverse();
            let start = data.length - 6;
            let end = data.length - 3;
            let result = data.slice(start, end);
            return result.reverse();
        },
        getPosts(state) {
            return state.posts.slice(0, 4).short;
        },
    },
})
export default store;
