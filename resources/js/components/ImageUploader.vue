<template>
    <div>
        <div class="w-96 bg-white rounded-lg shadow flex flex-col items-center p-8">
            <h1 class="text-xl text-gray-800">
                Upload Your Image
            </h1>

            <p class="mt-4 text-xs text-gray-500">
                File should be: Jpeg, Png ...
            </p>

            <div
                class="mt-6 w-full flex flex-col items-center bg-gray-100 p-8 border border-dashed border-blue-300 rounded-lg"
                @drop.prevent="onDropFile"
                @dragover.prevent
            >
                <UploadImage class="w-28 h-28"/>

                <p class="mt-8 text-sm text-gray-400">
                    Drag & Drop your image here
                </p>
            </div>

            <div class="mt-4 text-sm text-gray-500">or</div>

            <label
                class="mt-6 inline-block bg-blue-500 hover:bg-blue-400 px-4 py-2 text-xs text-white leading-5 rounded-lg">
                <input class="hidden" type="file" @change.prevent="onSelectFile">
                <span>Choose a file</span>
            </label>
        </div>

        <div class="w-96 bg-white rounded-lg shadow p-8">
            <h2 class="text-xl text-gray-800">
                Uploading
            </h2>

            <div class="mt-4 w-full h-2 bg-gray-200 rounded-full">
                <div class="bg-blue-500 rounded-full h-full" :style="'width: '+uploadPercentage+'%'"></div>
            </div>
        </div>
    </div>
</template>

<script>
import UploadImage from "./UploadImage";

export default {
    name: "ImageUploader",
    data() {
        return {
            file: null,
            uploadPercentage: 0
        }
    },
    methods: {
        onDropFile(e) {
            let droppedFiles = e.dataTransfer.files;
            this.file = droppedFiles[0] || null;
            this.upload();
        },
        onSelectFile(e) {
            let selectedFiles = e.target.files;
            this.file = selectedFiles[0] || null;
            this.upload();
        },
        upload() {
            let formData = new FormData();
            formData.append('image', this.file);
            axios.post('/api/images', formData, {
                onUploadProgress: (progressEvent) => {
                    let uploadPercentage = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ));
                    console.log(uploadPercentage);
                    this.uploadPercentage = uploadPercentage;
                }
            }).then(function (response) {
                console.log(response);
            })
                .catch(function (error) {
                    console.log(error);
                });
            ;

        }
    },
    components: {UploadImage},
}
</script>

<style scoped>

</style>
