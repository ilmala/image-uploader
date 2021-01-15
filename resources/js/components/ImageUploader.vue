<template>
    <div class="py-12">
        <main>
            <Card v-if="state==='STATE_START'" class="w-96 flex flex-col items-center">
                <template v-slot:header>
                    Upload Your Image
                </template>

                <p class="mt-4 text-xxs text-gray-400">
                    File should be: Jpeg, Png ...
                </p>

                <div
                    class="mt-7 w-full flex flex-col items-center bg-gray-100 p-8 border border-dashed border-blue-300 rounded-2xl"
                    @drop.prevent="onDropFile"
                    @dragover.prevent
                >
                    <UploadImage />

                    <p class="mt-8 text-xs text-gray-400">
                        Drag & Drop your image here
                    </p>
                </div>

                <div class="mt-4 text-sm text-gray-500">or</div>

                <label role="button">
                    <input class="hidden" ref="file" type="file" @change.prevent="onSelectFile">
                    <PrimaryButton @click.native.prevent="$refs.file.click()" class="mt-4">
                        Choose a file
                    </PrimaryButton>
                </label>
            </Card>

            <Card v-if="state==='STATE_LOADING'" class="w-96">
                <template v-slot:header>
                    <span>Uploading</span>
                    <span>{{ uploadPercentage }}%</span>
                </template>

                <div class="mt-4 w-full h-2 bg-gray-200 rounded-full">
                    <div class="bg-blue-500 rounded-full h-full" :style="'width: '+uploadPercentage+'%'"></div>
                </div>

                <div class="mt-4" v-if="showError">
                    <p class="text-xs text-red-600">
                        An error has occurred. Please try again later.
                        <a href="#" class="text-gray-400 hover:text-blue-500 transition duration-200"
                           @click.prevent="restart">
                            go back.
                        </a>
                    </p>
                </div>
            </Card>

            <Card v-if="state==='STATE_END'" class="w-96 flex flex-col items-center">

                <template v-slot:header>
                    <div class="w-9 h-9 mx-auto flex justify-center items-center rounded-full bg-green-600 text-white">
                        <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="mt-3">Upload Successfully!</span>
                </template>

                <img class="mt-6 w-full bg-gray-100 rounded-2xl object-cover" :src="url" alt="uploaded image">

                <div class="mt-6 w-full flex justify-between items-center space-x-1 bg-gray-100 border border-gray-200 rounded-2xl p-1">
                    <div class="text-xxs text-gray-800 truncate pl-2">
                        {{ url }}
                    </div>
                    <PrimaryButton
                        v-clipboard="() => url"
                    >
                        Copy link
                    </PrimaryButton>
                </div>

                <a href="#" class="mt-6 text-sm text-gray-400 hover:text-blue-500 transition duration-200"
                @click.prevent="restart">
                    Upload another image
                </a>
            </Card>
        </main>

        <footer class="py-8 text-xs text-gray-600 text-center">
            <p>
                Created by <a href="https://github.com/ilmala/image-uploader" class="text-blue-600">@ilmala</a>
            </p>
        </footer>
    </div>
</template>

<script>
import UploadImage from "./UploadImage";
import {STATE_START, STATE_LOADING, STATE_END} from "../upload-state";
import Card from "./Card";
import PrimaryButton from "./PrimaryButton";

export default {
    name: "ImageUploader",
    data() {
        return {
            file: null,
            uploadPercentage: 0,
            url: null,
            state: STATE_START,
            showError: false,
        }
    },
    methods: {
        restart() {
            this.file = null;
            this.uploadPercentage = 0;
            this.url = null;
            this.state = STATE_START;
            this.showError = false;
        },
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
                    this.showError = true;
                });
            ;

        }
    },
    components: {
        Card,
        UploadImage,
        PrimaryButton
    },
}
</script>

<style scoped>

</style>
