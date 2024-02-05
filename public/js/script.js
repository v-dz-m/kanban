const formTask = document.getElementById('addTask');
const task = document.getElementById('task');

formTask.addEventListener('submit', (event) => {
    if (task.value != '') {
        addCard(task.value);
        task.value = '';
    }
    event.preventDefault();
})

const modalBlock = document.getElementById('exampleModalFullscreen');

modalBlock.addEventListener('shown.bs.modal', async function () {
    const tableBody = document.querySelector('#exampleModalFullscreen tbody');
    const response = await taskArchiveGet();

    response.forEach(element => {
        const dataRow = document.createElement('tr');
        dataRow.innerHTML = `<th scope="row">${element.id}</th><td>${element.title}</td>
        <td>${element.author_user_id}</td><td>${new Date(element.created_at).toLocaleString()}</td>
        <td><button type="button" class="btn btn-danger btn-sm">Удалить</button></td>`;
        tableBody.appendChild(dataRow);
    });

    console.log(response);
})

modalBlock.addEventListener('hidden.bs.modal', function () {
    const tableBody = document.querySelector('#exampleModalFullscreen tbody');
    tableBody.innerHTML = '';
});

async function addCard(value) {
    const created = document.querySelector('#created');
    const newCard = document.createElement("div");
    newCard.classList.add('card');
    newCard.draggable = true;
    newCard.innerHTML = `
    <div class="content"><small></small><p class="cardBody">`+ value + `</p></div>`;
    newCard.addEventListener('dragstart', dragStart);
    newCard.addEventListener('drag', drag);
    newCard.addEventListener('dragend', dragEnd);

    const response = await taskStore(value);
    console.log(response);
    newCard.dataset.id = response;
    newCard.querySelector('small').textContent = "задача#" + response;

    created.appendChild(newCard);
}

const cards = document.querySelectorAll('.card');
const dropZones = document.querySelectorAll('.dropZone');

cards.forEach((card) => {
    card.addEventListener('dragstart', dragStart);
    card.addEventListener('drag', drag);
    card.addEventListener('dragend', dragEnd);
    card.addEventListener('click', cardClick)
})

function dragStart() {
    dropZones.forEach(dropZone => dropZone.classList.add('highlight'));
    this.classList.add('dragging');
}

function drag() {

}

async function dragEnd() {
    dropZones.forEach(dropZone => dropZone.classList.remove('highlight'));
    this.classList.remove('dragging');

    const id = this.dataset.id;
    const status = this.closest('.dropZone').dataset.status;

    if (this.parentElement.id === 'archive') {
        taskToArchive(this);
    } else if (this.parentElement.id === 'garbage') {
        taskToGarbage(this);
    }

    let response = (status !== 'DELETE') ? await taskUpdateStatus(id, status) : await taskDelete(id);

    console.log(response);
}

async function cardClick(e) {
    if (e.target.matches('p.cardBody')) {
        const container = document.getElementById("taskModalFullscreen");
        const modal = new bootstrap.Modal(container);

        const id = e.target.closest('.card').dataset.id;
        let response = await taskEdit(id);
        const info = response.info;
        console.log(info);

        const idLabel = container.querySelector('#taskId')
        const titleInput = container.querySelector('#taskFormControlInput1');
        const descriptionInput = container.querySelector('#taskFormControlTextarea1');
        const authorRow = container.querySelector('#taskAuthor');
        const createdRow = container.querySelector('#taskCreated');

        let [title, description, status, author, time] = [info['title'], info['description'], info['status'], info['author'], info['time']];

        titleInput.value = title;
        descriptionInput.textContent = description;
        idLabel.textContent = id;
        authorRow.textContent = author;
        createdRow.textContent = time;

        container.querySelector('#unzipTask').hidden = (status !== 6);

        modal.show();
    }
}

dropZones.forEach(dropZone => {
    dropZone.addEventListener('dragenter', dragEnter);
    dropZone.addEventListener('dragover', dragOver);
    dropZone.addEventListener('dragleave', dragLeave);
    dropZone.addEventListener('drop', drop);
})

function dragEnter() {

}

function dragOver() {
    this.classList.add('over');

    const cardBeingDragged = document.querySelector('.dragging');

    this.appendChild(cardBeingDragged);
}

function dragLeave() {
    this.classList.remove('over');
}

function drop() {
    this.classList.remove('over');
}
