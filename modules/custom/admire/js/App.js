alert();
document.addEventListener('load', () => {
    const App = new Vue({
        el: '#App',
        data: {
            events: [1, 2, 3]
        }
    });
});