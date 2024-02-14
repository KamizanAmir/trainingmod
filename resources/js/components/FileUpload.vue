<template>
    <div>
        <input type="file" @change="fileSelected" ref="fileInput">
        <button @click="uploadFile">Upload</button>
        <div v-if="files.length">
            <h3>Uploaded Files</h3>
            <ul>
                <li v-for="file in files" :key="file.id">{{ file.name }}</li>
            </ul>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            selectedFile: null,
            files: [], // Add this line to store files
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };
    },
    mounted() {
        this.fetchFiles(); // Fetch files when component mounts
    },
    methods: {
        fileSelected(event) {
            // Store the selected file on data property
            this.selectedFile = event.target.files[0];
        },
        uploadFile() {
            if (!this.selectedFile) {
                alert('Please select a file first.');
                return;
            }
            
            let formData = new FormData();
            formData.append('file', this.selectedFile);
            formData.append('_token', this.csrf); // Include CSRF token in the form data
            
            axios.post('/api/upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': this.csrf // Include CSRF token in the request headers
                }
            }).then(response => {
                console.log(response.data);
                // Handle success, for example clear the selected file
                this.clearSelection();
                alert('File uploaded successfully');
            }).catch(error => {
                console.log(error);
                // Handle error
                alert('There was an error uploading the file');
            });
        },
        clearSelection() {
            // Clear the file input after upload or if you want to reset it
            this.$refs.fileInput.value = '';
            this.selectedFile = null;
        },
        fetchFiles() {
            axios.get('/api/files')
                .then(response => {
                    this.files = response.data;
                })
                .catch(error => {
                    console.error('There was an error fetching the files', error);
                });
        }
    }
}
</script>
