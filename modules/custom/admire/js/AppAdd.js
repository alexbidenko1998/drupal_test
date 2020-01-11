const AppAdd = new Vue({
    el: '#AppAdd',
    data: {
        title: '',
        description: '',
        isPaid: false,
        price: 0,
        video: null,
        preview: null
    },
    computed: {
        isDisabled() {
            return this.title && this.description && (!this.isPaid || this.price > 0) && this.video && this.preview;
        }
    },
    methods: {
        addVideo(event) {
            if(event.target.files.length > 0) {
                this.video = event.target.files[0];
            }
        },
        addPreview(event) {
            if(event.target.files.length > 0) {
                this.preview = event.target.files[0];
            }
        },
        submit() {
            const form = new FormData();
            form.append('title', this.title);
            form.append('description', this.description);
            form.append('isPaid', this.isPaid);
            form.append('price', this.price);
            form.append('video', this.video);
            form.append('preview', this.preview);
            fetch('https://admire.social/api/drupal/video', {
                method: 'POST',
                body: form
            }).then(response => response.json())
                .then(data => {
                    alert('Видео успешно добавлено');
                });
        }
    }
});