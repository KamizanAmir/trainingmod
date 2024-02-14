<template>
    <div style="margin-top: 10px;" class="section-title">
        <input type="file" @change="fileSelected" ref="fileInput">
        <button style="text-align: center;" @click="uploadFile">Upload</button>
        <hr>
        <div v-if="files.length" class="section-container">
            <h3 style="text-align: center; color: white;">Uploaded Files</h3>
            <ul style="text-align: center;align-items: center; color: white;">
                <li v-for="file in files" :key="file.id">
                    {{ file.name }}
                    <!-- Download Button -->
                    <button @click="downloadFile(file.id)" class="view-button">Download</button>
                </li>
            </ul>
        </div>
    </div>
</template>
<style scoped>
.section-container {
  margin-bottom: 20px; /* Spacing between sections */
  border: 1px solid #0d3f80; /* Light grey border */
  border-radius: 6px; /* Rounded corners */
  background-color: #343a40; /* Light grey background */
  padding: 20px; /* Padding around the content */
}

.section-title {
  text-align: center;
  margin-bottom: 15px; /* Spacing after the title */
}

.table-container {
  background-color: white; /* White background for the content area */
  border: 1px solid #ccc; /* Light border for the table/container */
  border-radius: 4px; /* Slight rounding of corners */
  overflow: hidden; /* Ensures no overflow of child elements */
}

.alert-container {
    display: flex;
    justify-content: center; /* Changed to center */
    align-items: center;
    margin-bottom: 10px; /* or any spacing you want between items */
    text-align: center; /* Center align the text */
}

/* Ensure the last alert-container in each table-container does not have a bottom border */
.table-container .alert-container:last-child {
  border-bottom: none;
}

.view-button {
    background-color: rgb(235, 199, 19); /* Blue background */
    color: black; /* White text */
    padding: 10px 20px; /* Padding inside the button */
    text-align: center; /* Center text inside the button */
    text-decoration: none; /* Remove underline from text */
    display: inline-block; /* Allow width and height to be set */
    font-size: 16px; /* Set font size */
    margin: 4px 2px; /* Spacing around the button */
    cursor: pointer; /* Change mouse cursor to pointer on hover */
    border: none; /* Remove border */
    border-radius: 5px; /* Rounded corners */
}
</style>
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
        viewFile(fileId) {
            window.open(`/api/files/view/${fileId}`, '_blank');
        },
        downloadFile(fileId) {
            window.location.href = `/api/files/download/${fileId}`;
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
