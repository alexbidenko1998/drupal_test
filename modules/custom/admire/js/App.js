const App = new Vue({
    el: '#App',
    data: {
        sorting: 'id',
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
            return this.events.sort(a, b => {
                if(a[this.sortin] > b[this.sortin]) return 1;
                else return -1;
            });
        }
    }
});