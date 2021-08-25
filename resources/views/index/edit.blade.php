<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            工事情報編集
        </h2>
    </x-slot>

    <!-- パンくずリスト -->
    <div class="flex items-center py-2 px-8 bg-white shadow-xl border-t-2 border-gray-200">
        <a href="{{route('dashboard')}}" class="text-blue-500 pr-3">工事物品管理トップ</a>
        <i class="fas fa-chevron-right text-gray-500 mr-3"></i>
        <a href="{{route('edit', ['id' => 1])}}" class="text-blue-500 pr-3">工事情報編集</a>
    </div>

    <div class="py-12">
        <div>
            <form class="max-w-7xl lg:container m-auto bg-white overflow-hidden shadow-xl py-8 px-16" action="" method="post">
                <div>
                    <h2 class="text-xl border-b border-l-8 pl-3 border-gray-500">工事情報</h2>

                    <div class="flex pt-10">
                        <div class="flex flex-col">
                            <label for="contract_date">契約日</label>
                            <input type="date" id="contract_date" name="contract_date" value="" class="mt-1">
                        </div>
                        <div class="flex flex-col ml-10">
                            <label for="construct_date">工事日</label>
                            <input type="date" id="construct_date" name="construct_date" value="" class="mt-1">
                        </div>
                    </div>

                    <div class="flex flex-col pt-10 w-1/4">
                        <label for="customer_name">お客様名</label>
                        <input type="text" id="customer_name" name="customer_name" value="" class="mt-1">
                    </div>
                    <div class="flex flex-col pt-10 w-2/4">
                        <label for="project_title">案件名</label>
                        <input type="text" id="project_title" name="project_title" value="" class="mt-1">
                    </div>
                </div>

                <div>
                    <h2 class="mt-16 text-xl border-b border-l-8 pl-3 border-gray-500">発注物品</h2>

                    <script type="text/javascript">
                        window.onload = function() {
                            var URL_BLANK_IMAGE = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
                            var elDrop = document.getElementById('droparea');
                            var elFiles = document.getElementById('files');

                            elDrop.addEventListener('dragover', function(event) {
                                event.preventDefault();
                                event.dataTransfer.dropEffect = 'copy';
                                showDropping();
                            });

                            elDrop.addEventListener('dragleave', function(event) {
                                hideDropping();
                            });

                            elDrop.addEventListener('drop', function(event) {
                                event.preventDefault();
                                hideDropping();

                                var files = event.dataTransfer.files;
                                showFiles(files);
                            });

                            document.addEventListener('click', function(event) {
                                var elTarget = event.target;
                                if (elTarget.tagName === 'IMG') {
                                    var src = elTarget.src;
                                    var w = window.open('about:blank');
                                    var d = w.document;

                                    d.open();
                                    d.write('<img src="' + src + '" />');
                                    d.close();
                                }
                            });

                            function showDropping() {
                                elDrop.classList.add('dropover');
                            }

                            function hideDropping() {
                                elDrop.classList.remove('dropover');
                            }

                            function showFiles(files) {
                                elFiles.innerHTML = '';

                                for (var i = 0, l = files.length; i < l; i++) {
                                    var file = files[i];
                                    var elFile = buildElFile(file);
                                    elFiles.appendChild(elFile);
                                }
                            }

                            function buildElFile(file) {
                                var elFile = document.createElement('li');

                                if (file.type.indexOf('image/') === 0) {
                                    var elImage = document.createElement('img');
                                    elImage.src = URL_BLANK_IMAGE;
                                    elFile.appendChild(elImage);

                                    attachImage(file, elImage);
                                }

                                return elFile;
                            }

                            function attachImage(file, elImage) {
                                var reader = new FileReader();
                                reader.onload = function(event) {
                                    var src = event.target.result;
                                    elImage.src = src;
                                    elImage.setAttribute('title', file.name);
                                };
                                reader.readAsDataURL(file);
                            }
                        }
                    </script>

                    <div effectallowed="move" id="droparea" class="bg-gray-100 rounded-lg border-dashed border-4 p-10 mt-10">ここにファイルをドロップ</div>
                    <ul id="files"></ul>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>