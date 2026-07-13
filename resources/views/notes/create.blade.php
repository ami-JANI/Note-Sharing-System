<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 28px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
                Upload Notes
            </h2>
            <p style="font-size: 15px; color: rgb(91, 104, 133); margin-top: 4px;">Share your study notes with fellow students</p>
        </div>
    </x-slot>

    <div style="padding: 48px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="max-width: 600px; margin: 0 auto;">

            {{-- Breadcrumb --}}
            <nav style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: rgb(91, 104, 133); margin-bottom: 28px;">
                <a href="{{ route('dashboard') }}" style="color: rgb(91, 104, 133); text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='rgb(138, 28, 36)'" onmouseout="this.style.color='rgb(91, 104, 133)'">Browse</a>
                <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
                <span style="font-weight: 600; color: rgb(27, 42, 74);">Upload</span>
            </nav>

            <form method="POST" action="{{ route('notes.store') }}" enctype="multipart/form-data" style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 32px;">
                @csrf

                {{-- Title --}}
                <div style="margin-bottom: 24px;">
                    <label for="title" style="display: block; font-size: 14px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 6px;">Note title</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           placeholder="e.g. Elasticity & Market Demand"
                           style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 15px; color: rgb(27, 42, 74); outline: none; transition: border-color 0.15s;"
                           onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">
                    @error('title')
                        <p style="font-size: 13px; color: #e74c3c; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Course Title --}}
                <div style="margin-bottom: 24px;">
                    <label for="course_title" style="display: block; font-size: 14px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 6px;">Course title</label>
                    <input type="text" id="course_title" name="course_title" value="{{ old('course_title') }}" required
                           placeholder="e.g. Microeconomics"
                           style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 15px; color: rgb(27, 42, 74); outline: none; transition: border-color 0.15s;"
                           onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">
                    @error('course_title')
                        <p style="font-size: 13px; color: #e74c3c; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Course Number --}}
                <div style="margin-bottom: 24px;">
                    <label for="course_no" style="display: block; font-size: 14px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 6px;">Course code</label>
                    <input type="text" id="course_no" name="course_no" value="{{ old('course_no') }}" required
                           placeholder="e.g. ECON 201"
                           style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 15px; color: rgb(27, 42, 74); outline: none; transition: border-color 0.15s;"
                           onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">
                    @error('course_no')
                        <p style="font-size: 13px; color: #e74c3c; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Department --}}
                <div style="margin-bottom: 24px;">
                    <label for="department" style="display: block; font-size: 14px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 6px;">Department</label>
                    <select id="department" name="department" required
                            style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 15px; color: rgb(27, 42, 74); background: white; outline: none;">
                        <option value="">Select department</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept }}" {{ old('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                    @error('department')
                        <p style="font-size: 13px; color: #e74c3c; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Semester --}}
                <div style="margin-bottom: 24px;">
                    <label for="semester_id" style="display: block; font-size: 14px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 6px;">Semester</label>
                    <select id="semester_id" name="semester_id" required
                            style="width: 100%; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 15px; color: rgb(27, 42, 74); background: white; outline: none;">
                        <option value="">Select semester</option>
                        @foreach ($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
                                {{ $semester->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('semester_id')
                        <p style="font-size: 13px; color: #e74c3c; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Credit Price --}}
                <div style="margin-bottom: 24px;">
                    <label for="credit_price" style="display: block; font-size: 14px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 6px;">Credit price</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="number" id="credit_price" name="credit_price" value="{{ old('credit_price', 0) }}" min="0"
                               style="width: 120px; padding: 10px 14px; border: 1px solid rgba(27, 42, 74, 0.15); border-radius: 8px; font-size: 15px; color: rgb(27, 42, 74); outline: none; transition: border-color 0.15s;"
                               onfocus="this.style.borderColor='rgb(138, 28, 36)'" onblur="this.style.borderColor='rgba(27, 42, 74, 0.15)'">
                        <span style="font-size: 14px; color: rgb(91, 104, 133);">credits</span>
                    </div>
                    <p style="font-size: 13px; color: rgb(91, 104, 133); margin-top: 6px;">Set to 0 for free notes.</p>
                    @error('credit_price')
                        <p style="font-size: 13px; color: #e74c3c; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- File Upload --}}
                <div style="margin-bottom: 28px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: rgb(27, 42, 74); margin-bottom: 6px;">Note file</label>
                    <label for="file" id="file-dropzone" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 32px; border: 2px dashed rgba(27, 42, 74, 0.15); border-radius: 12px; cursor: pointer; transition: border-color 0.15s, background 0.15s;"
                           onmouseover="this.style.borderColor='rgb(138, 28, 36)'; this.style.background='rgba(138, 28, 36, 0.03)'"
                           onmouseout="this.style.borderColor='rgba(27, 42, 74, 0.15)'; this.style.background='transparent'">
                        <svg style="width: 36px; height: 36px; color: rgb(91, 104, 133); margin-bottom: 8px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                        <span style="font-size: 14px; color: rgb(27, 42, 74); font-weight: 500;">Click to upload</span>
                        <span style="font-size: 13px; color: rgb(91, 104, 133); margin-top: 4px;">PDF or DOCX up to 20 MB</span>
                    </label>
                    <input type="file" id="file" name="file" accept=".pdf,.docx" style="display: none;" onchange="handleFileSelect(this)">
                    <p id="file-name" style="font-size: 13px; color: rgb(91, 104, 133); margin-top: 8px;"></p>
                    @error('file')
                        <p style="font-size: 13px; color: #e74c3c; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" id="submit-btn" disabled
                        style="width: 100%; padding: 12px 24px; background: rgba(27, 42, 74, 0.2); color: rgb(175, 182, 201); border-radius: 8px; font-size: 15px; font-weight: 600; cursor: not-allowed; transition: all 0.2s; border: none;">
                    Upload Note
                </button>
            </form>

        </div>
    </div>

    <script>
        function handleFileSelect(input) {
            var fileName = document.getElementById('file-name');
            var submitBtn = document.getElementById('submit-btn');
            var dropzone = document.getElementById('file-dropzone');

            if (input.files && input.files.length > 0) {
                var file = input.files[0];
                var validTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                var maxSize = 20 * 1024 * 1024;

                if (!validTypes.includes(file.type)) {
                    fileName.textContent = 'Invalid file type. Please select a PDF or DOCX.';
                    fileName.style.color = '#e74c3c';
                    input.value = '';
                    return;
                }

                if (file.size > maxSize) {
                    fileName.textContent = 'File is too large. Maximum size is 20 MB.';
                    fileName.style.color = '#e74c3c';
                    input.value = '';
                    return;
                }

                fileName.textContent = file.name;
                fileName.style.color = 'rgb(27, 42, 74)';
                dropzone.style.borderColor = 'rgba(138, 28, 36, 0.3)';
                dropzone.style.background = 'rgba(138, 28, 36, 0.03)';

                submitBtn.disabled = false;
                submitBtn.style.background = 'rgb(138, 28, 36)';
                submitBtn.style.color = 'rgb(251, 248, 243)';
                submitBtn.style.cursor = 'pointer';
                submitBtn.style.borderColor = 'rgb(138, 28, 36)';
            } else {
                fileName.textContent = '';
                submitBtn.disabled = true;
                submitBtn.style.background = 'rgba(27, 42, 74, 0.2)';
                submitBtn.style.color = 'rgb(175, 182, 201)';
                submitBtn.style.cursor = 'not-allowed';
            }
        }
    </script>
</x-app-layout>
