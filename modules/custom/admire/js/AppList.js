const AppList = new Vue({
    el: '#AppList',
    data: {
        sorting: 'id',
        filterTitle: '',
        videos: []
    },
    created() {
        fetch('https://admire.social/api/drupal/video')
            .then(response => response.json())
            .then(data => {
                this.videos = data.map(el => {
                    el.preview = 'https://admire.social/drupal/test/preview/' + el.preview;
                    return el;
                });
            });
    },
    computed: {
        filteredVideos() {
            return this.videos.filter(el => {
                return el.title.indexOf(this.filterTitle) > -1;
            }).sort((a, b) => {
                if(a[this.sorting] > b[this.sorting]) return 1;
                else return -1;
            });
        }
    }
});