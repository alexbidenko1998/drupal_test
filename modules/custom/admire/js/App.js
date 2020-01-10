const App = new Vue({
    el: '#App',
    data: {
        sorting: 'id',
        filterName: '',
        events: []
    },
    created() {
        fetch('https://admire.social/api/v1.0/getEvents.php?latitude=37&longitude=44')
            .then(response => response.json())
            .then(data => {
                this.events = data;
            });
    },
    computed: {
        filteredEvents() {
            return this.events.filter(el => {
                return el.name.indexOf(this.filterName) > -1;
            }).sort((a, b) => {
                if(a[this.sorting] > b[this.sorting]) return 1;
                else return -1;
            });
        }
    },
    methods: {
        update() {
            fetch('https://admire.social/api/v1.0/getEvents.php?latitude=37&longitude=44')
                .then(response => response.json())
                .then(data => {
                    this.events = data;
                    $('#success').modal('show');
                });
        }
    }
});