<template>
    <div>
      <input type="file" @change="selectFile" />
      <button @click="uploadFile">Upload</button>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        selectedFile: null,
      };
    },
    methods: {
      selectFile(event) {
        this.selectedFile = event.target.files[0];
      },
      async uploadFile() {
        if (!this.selectedFile) {
          alert('Please select a file first.');
          return;
        }
  
        let formData = new FormData();
        formData.append('file', this.selectedFile);
  
        try {
          await this.$axios.post('/api/files/upload', formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          });
          alert('File uploaded successfully.');
        } catch (error) {
          console.error('Error uploading file:', error);
          alert('Error uploading file.');
        }
      },
    },
  };
  </script>  