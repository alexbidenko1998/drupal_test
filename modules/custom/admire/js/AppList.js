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
                    el.preview = `<img class="w-100" style="max-width: 200px;" src="https://admire.social/drupal/test/preview/${el.preview}">`;
                    el.link = `<a href="update/${el.id}">Редактировать</a>`;
                    return el;
                });
            });
        document.getElementById('AppList').classList.remove('d-none');
    },
    computed: {
        filteredVideos() {
            return this.videos.filter(el => {
                return el.title.toLowerCase().indexOf(this.filterTitle.toLowerCase()) > -1;
            }).sort((a, b) => {
                if(a[this.sorting] > b[this.sorting]) return 1;
                else return -1;
            });
        }
    }
});