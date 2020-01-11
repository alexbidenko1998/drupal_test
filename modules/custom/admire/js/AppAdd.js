const AppAdd = new Vue({
    el: '#AppAdd',
    data: {
        title: '',
        description: '',
        isPaid: false,
        price: 0,
        video: null,
        preview: null,

        previewImage: null
    },
    computed: {
        isDisabled() {
            return this.title && this.description && (!this.isPaid || +this.price > 0) && this.video && this.preview;
        }
    },
    methods: {
        addVideo() {
            const files = document.getElementById('inputVideo').files;
            if (!files.length)
                return;
            this.video = files[0];
        },
        addPreview() {
            const files = document.getElementById('inputPreview').files;
            if (!files.length)
                return;
            this.preview = files[0];

            const reader = new FileReader();
            const vm = this;

            reader.onload = (e) => {
                vm.previewImage = e.target.result;
            };
            reader.readAsDataURL(this.preview);
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
            }).then(response => response.text())
                .then(data => {
                    console.log(data);
                    alert('Видео успешно добавлено');
                });
        }
    }
});