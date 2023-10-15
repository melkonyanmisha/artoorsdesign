<div class="col-lg-12 val_8">
    <div class="form-row align-items-center" id="chunk_upload_wrapper">
        <div class="form-group">
            <label for="fileInput">Choose File</label>
            <input type="file" class="form-control-file" id="fileInput"
                   name="file">
        </div>
        <button type="button" class="btn primary-btn small fix-gr-bg"
                id="uploadBtn">Upload
        </button>

        <!-- Progress bar and success icon container -->
        <div class="progress-icon-container mt-2"
             style="display: none; width: 100%;">
            <div class="progress" style="width: 100%; float: left;">
                <div class="progress-bar" role="progressbar"
                     style="width: 0%; height: 100%; background-color: #7C32FF;"
                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%
                </div>
            </div>
            <div class="error-message" style="display: none; color: red">Upload
                failed. Please try again.
            </div>
            <i id="successIcon" class="fas fa-check-circle"
               style="display: none; color: #7c32ff; float: right;"></i>
        </div>
    </div>
</div>

<script>
    function generateRandomText(length) {
        const characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        let randomText = '';

        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * characters.length);
            randomText += characters.charAt(randomIndex);
        }

        return randomText;
    }

    document.getElementById('uploadBtn').addEventListener('click', function () {
        const fileInput = document.getElementById('fileInput');
        const progressBarContainer = document.querySelector('.progress-icon-container');
        const progressBar = document.querySelector('.progress');
        const progressBarText = progressBar.querySelector('.progress-bar');
        const errorMessage = document.querySelector('.error-message');
        progressBarText.style.backgroundColor = '#7C32FF';
        const folderName = generateRandomText(10);


        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const chunkSize = 2 * 1024 * 1024; // 2 MB chunk size (adjust as needed)
            let start = 0;
            let chunkIndex = 0;
            const totalChunks = Math.ceil(file.size / chunkSize);

            errorMessage.style.display = 'none';
            progressBar.style.display = 'block';
            progressBarContainer.style.display = 'inline-block';
            progressBarText.style.width = '0%';
            progressBarText.textContent = '0%';

            function uploadNextChunk() {
                const chunk = file.slice(start, start + chunkSize);
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('file_path', file.name);
                formData.append('chunk_index', chunkIndex);
                formData.append('chunk', chunk);
                formData.append('folder_name', folderName);

                fetch('{{ route("upload.chunk") }}', {
                    method: 'POST',
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        chunkIndex++;
                        start += chunkSize;

                        const percent = Math.min(100, Math.round((chunkIndex / totalChunks) * 100));
                        progressBarText.style.width = percent + '%';
                        progressBarText.textContent = percent + '%';

                        if (chunkIndex < totalChunks) {
                            uploadNextChunk();
                        } else {
                            completeUpload();
                        }
                    })
                    .catch(error => {
                        showUploadFailedError();
                        console.error('Error uploading chunk:', error);
                    });
            }

            function completeUpload() {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('file_path', file.name);
                formData.append('folder_name', folderName);

                fetch('{{ route("upload.complete") }}', {
                    method: 'POST',
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('File upload complete:', data);
                        jQuery('#video_link').val(data.file_path);

                        // Show the success icon
                        const successIcon = document.getElementById('successIcon');
                        successIcon.style.display = 'inline-block';

                        // Calculate the new width for the progress bar text
                        const progressBar = document.querySelector('.progress');
                        const progressBarText = progressBar.querySelector('.progress-bar');
                        const progressBarWidth = progressBar.offsetWidth; // Total width of the progress bar
                        const successIconWidth = successIcon.offsetWidth; // Width of the success icon
                        const progressBarTextWidth = progressBarWidth - successIconWidth - 5; // Calculate new width
                        progressBarText.style.width = progressBarTextWidth + 'px';
                        progressBar.style.width = progressBarTextWidth + 'px';
                        $("#chunk_upload_wrapper #fileInput").val('');
                    })
                    .catch(error => {
                        showUploadFailedError();
                        console.error('Error completing upload:', error);
                    });
            }

            function showUploadFailedError() {
                errorMessage.style.display = 'block';
                errorMessage.style.color = 'red';
                progressBarText.style.backgroundColor = 'red';
            }

            uploadNextChunk();
        }
    });
</script>
