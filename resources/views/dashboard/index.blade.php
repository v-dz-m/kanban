@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/kanban.css') }}">
@endsection

@section('content')
    <div class="kanban">
        <div class="boards">
            <div class="board created">
                <div class="status"></div>
                <h2>СОЗДАНА</h2>
                <div class="dropZone" id="created" data-status="1">
                    @isset($tasks[1])
                        @foreach ($tasks[1] as $item)
                            @include('dashboard.partials.task', [
                                'id' => $item->id,
                                'title' => $item->title,
                            ])
                        @endforeach
                    @endisset
                </div>
            </div>
            <div class="board todo">
                <div class="status"></div>
                <h2>К ВЫПОЛНЕНИЮ</h2>
                <div class="dropZone" id="todo" data-status="2">
                    @isset($tasks[2])
                        @foreach ($tasks[2] as $item)
                            @include('dashboard.partials.task', [
                                'id' => $item->id,
                                'title' => $item->title,
                            ])
                        @endforeach
                    @endisset
                </div>
            </div>
            <div class="board doing">
                <div class="status"></div>
                <h2>В РАБОТЕ</h2>
                <div class="dropZone" id="doing" data-status="3">
                    @isset($tasks[3])
                        @foreach ($tasks[3] as $item)
                            @include('dashboard.partials.task', [
                                'id' => $item->id,
                                'title' => $item->title,
                            ])
                        @endforeach
                    @endisset
                </div>
            </div>
            <div class="board testing">
                <div class="status"></div>
                <h2>НА ПРОВЕРКЕ</h2>
                <div class="dropZone" id="testing" data-status="4">
                    @isset($tasks[4])
                        @foreach ($tasks[4] as $item)
                            @include('dashboard.partials.task', [
                                'id' => $item->id,
                                'title' => $item->title,
                            ])
                        @endforeach
                    @endisset
                </div>
            </div>
            <div class="board done">
                <div class="status"></div>
                <h2>ГОТОВО!</h2>
                <div class="dropZone" id="done" data-status="5">
                    @isset($tasks[5])
                        @foreach ($tasks[5] as $item)
                            @include('dashboard.partials.task', [
                                'id' => $item->id,
                                'title' => $item->title,
                            ])
                        @endforeach
                    @endisset
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="task">
                <h4><label for="task">ДОБАВИТЬ ЗАДАЧУ</label></h4>
                <form id="addTask" action="">
                    <input id="task" name="task" type="text" autocomplete="off">
                    <button type="submit"><img src="assets/plus.svg" alt="Add task"></button>
                </form>
            </div>
            <div class="garbage archive">
                <div class="dropZone" id="archive" data-status="6">
                    <h4>Архивировать
                        <span data-bs-toggle="modal" data-bs-target="#exampleModalFullscreen">
                            [{{ isset($tasks[6]) ? $tasks[6]->count() : 0 }}]
                        </span>
                    </h4>
                </div>
            </div>
            <div class="garbage">
                <div class="dropZone" id="garbage" data-status="DELETE">
                    <h4>Удалить</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModalFullscreen" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel"
         aria-hidden="true">
        <!-- Full screen modal -->
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-4" id="exampleModalFullscreenLabel">Архив задач</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Название задачи</th>
                            <th scope="col">Создатель</th>
                            <th scope="col">Время создания</th>
                            <th scope="col">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="taskModalFullscreen" tabindex="-1" aria-labelledby="taskModalFullscreenLabel"
         aria-hidden="true">
        <!-- Full screen modal -->
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-4" id="taskModalFullscreenLabel">Информация о задаче#<span
                            id="taskId"></span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="taskFormControlInput1" class="form-label">Название задачи</label>
                        <input type="text" class="form-control form-control-lg" id="taskFormControlInput1" required
                               placeholder="Введите название задачи (не более 255 символов, обязательное поле)">
                    </div>
                    <div class="mb-3">
                        <label for="taskeFormControlTextarea1" class="form-label">Описание задачи</label>
                        <textarea class="form-control" id="taskFormControlTextarea1" rows="3"
                                  placeholder="Вы можете добавить описание для данной задачи"></textarea>
                    </div>
                    <div class="mb-3">
                        <p>Создатель задачи: <span id="taskAuthor"></span></p>
                        <p>Время создания: <span id="taskCreated"></span></p>
                    </div>
                    <button type="button" class="btn btn-success" id="saveTask">Сохранить</button>
                    <button type="button" class="btn btn-primary" id="unzipTask">Разархивировать</button>
                    <button type="button" class="btn btn-danger" id="deleteTask">Удалить</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/script.js') }}"></script>
    <script>
        function taskToArchive(element) {
            console.log('archived');
            element.parentElement.removeChild(element);
        }

        function taskToGarbage(element) {
            console.log('removed');
            element.parentElement.removeChild(element);
        }

        async function taskUpdateStatus(id, status) {
            const url = "{{ route('tasks.index') }}/" + id;
            const response = await fetch(url, {
                method: 'PUT',
                headers: {
                    "X-CSRF-Token": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    status: status
                }),
            });

            return await response.text();
        }

        async function taskUpdateBody(id, title, description) {
            const url = "{{ route('tasks.index') }}/" + id;
            const response = await fetch(url, {
                method: 'PUT',
                headers: {
                    "X-CSRF-Token": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    title: title,
                    description: description,
                }),
            });

            return await response.text();
        }

        async function taskStore(title) {
            const url = "{{ route('tasks.store') }}";
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    "X-CSRF-Token": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    title: title
                }),
            });

            return await response.text();
        }

        async function taskDelete(id) {
            const url = "{{ route('tasks.index') }}/" + id;
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    "X-CSRF-Token": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Content-Type": "application/json",
                }
            });

            return await response.text();
        }

        async function taskArchiveGet() {
            const url = "{{ route('tasks.index') }}";
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    "Content-Type": "application/json",
                }
            })
            return await response.json();
        }

        async function taskEdit(id) {
            const url = `{{ route('tasks.index') }}/${id}/edit`;
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    "Content-Type": "application/json",
                }
            });

            return await response.json();
        }
    </script>
@endsection
