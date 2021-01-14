<template>
    <div class="py-12">
        <div v-if="state==='STATE_START'" class="w-96 bg-white rounded-xl shadow-md flex flex-col items-center p-8">
            <h1 class="text-xl text-gray-800">
                Upload Your Image
            </h1>

            <p class="mt-4 text-xs text-gray-500">
                File should be: Jpeg, Png ...
            </p>

            <div
                class="mt-6 w-full flex flex-col items-center bg-gray-100 p-8 border border-dashed border-blue-300 rounded-xl"
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
                class="mt-6 inline-block whitespace-nowrap bg-blue-500 hover:bg-blue-400 px-4 py-2 text-xs text-white leading-5 rounded-xl">
                <input class="hidden" type="file" @change.prevent="onSelectFile">
                <span>Choose a file</span>
            </label>
        </div>

        <div v-if="state==='STATE_LOADING'" class="w-96 bg-white rounded-xl shadow-md p-8">
            <h2 class="text-xl text-gray-800">
                Uploading
            </h2>

            <div class="mt-4 w-full h-2 bg-gray-200 rounded-full">
                <div class="bg-blue-500 rounded-full h-full" :style="'width: '+uploadPercentage+'%'"></div>
            </div>
        </div>

        <div v-if="state==='STATE_END'" class="w-96 bg-white rounded-xl shadow-md flex flex-col items-center p-8">

            <div class="w-8 h-8 flex justify-center items-center rounded-full bg-green-600 text-white">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h1 class="mt-4 text-xl text-gray-800">
                Upload Successfully!
            </h1>

            <img class="mt-6 w-full bg-gray-100 rounded-xl object-cover" :src="url" alt="uploaded image">

            <div class="mt-6 w-full flex items-center space-x-1 bg-gray-100 border border-gray-200 rounded-xl p-1">
                <div class="text-xs text-gray-800 truncate pl-2">
                    {{ url }}
                </div>
                <button
                    class="inline-block whitespace-nowrap bg-blue-500 hover:bg-blue-400 px-4 py-2 text-xs text-white leading-5 rounded-xl"
                    v-clipboard="() => url"
                >
                    Copy link
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import UploadImage from "./UploadImage";
import {STATE_START, STATE_LOADING, STATE_END} from "../upload-state";

export default {
    name: "ImageUploader",
    data() {
        return {
            file: null,
            uploadPercentage: 0,
            url: null,
            state: STATE_START
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
            this.state = STATE_LOADING;
            let formData = new FormData();
            formData.append('image', this.file);
            axios.post('/api/images', formData, {
                onUploadProgress: (progressEvent) => {
                    let uploadPercentage = parseInt(Math.round((progressEvent.loaded / progressEvent.total) * 100));
                    console.log(uploadPercentage);
                    this.uploadPercentage = uploadPercentage;
                }
            }).then((response) => {
                this.url = response.data.image_url;
                this.state = STATE_END;
            })
                .catch((error) => {
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
