const AppAdd = new Vue({
    el: '#AppAdd',
    data: {
        id: 0,
        videoData: null,
        title: '',
        description: '',
        isPaid: false,
        price: 0,
        video: null,
        preview: null,

        previewImage: null,
        deleteButton: '',
        videoLink: ''
    },
    created() {
        let videoId = document.getElementById('AppAdd').attributes['video-id'];
        if(!!videoId) {
            this.id = +videoId.value;
            fetch('https://admire.social/api/drupal/video/' + this.id)
                .then(response => response.json())
                .then(data => {
                    this.title = data.title;
                    this.description = data.description;
                    this.isPaid = data.isPaid === 1;
                    this.price = data.price;
                    this.previewImage = `<img class="w-100 my-3" style="max-width: 300px;" src="https://admire.social/drupal/test/preview/${data.preview}">`;

                    this.videoData = data;
                    this.videoLink = `<a href="https://admire.social/drupal/test/video/${this.videoData.video}" target="_blank">Посмотреть видео</a>`;

                    document.getElementById('AppAdd').classList.remove('d-none');
                });
            this.deleteButton = `<button class="btn btn-warning btn-block" onclick="AppAdd.delete()">Удалить</button>`;
        } else {
            document.getElementById('AppAdd').classList.remove('d-none');
        }
    },
    computed: {
        isDisabled() {
            return !(this.title && this.description && (!this.isPaid || +this.price > 0) && this.video && this.preview
                || this.id > 0) ;
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
                vm.previewImage = `<img src="${e.target.result}" class="w-100">`;
            };
            reader.readAsDataURL(this.preview);
        },
        submit() {
            const form = new FormData();
            form.append('title', this.title);
            form.append('description', this.description);
            form.append('isPaid', this.isPaid);
            form.append('price', this.price);
            if(this.video != null) {
                form.append('video', this.video);
            }
            if(this.preview != null) {
                form.append('preview', this.preview);
            }
            fetch('https://admire.social/api/drupal/video' + (this.id > 0 ? '/' + this.id : ''), {
                method: 'POST',
                body: form
            }).then(response => response.text())
                .then(data => {
                    console.log(data);
                    alert('Видео успешно добавлено');
                    window.location.assign('videos/list')
                });
        },
        delete() {
            fetch('https://admire.social/api/drupal/video/' + this.id, {
                method: 'DELETE'
            }).then(response => response.text())
                .then(data => {
                    console.log(data);
                    alert('Видео успешно удалено');
                    window.location.assign('videos/list')
                });
        }
    }
});