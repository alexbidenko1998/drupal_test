const App = new Vue({
    el: '#App',
    data: {
        events: []
    },
    created() {
        fetch('https://admire.social/api/v1.0/getEvents.php?latitude=37&longitude=44')
            .then(response => response.json())
            .then(data => {
                this.events = data;
            });
    }
});