<div class="dropdown">
    <a href="#" class="dropdown-toggle badge bg-red-lt form-label mb-0 fs-5 text-decoration-none" data-bs-toggle="dropdown">
        <svg class="mx-1 text-danger" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M833-41 718-156q-50 36-110 56T480-80q-85 0-158-30.5T195-195q-54-54-84.5-127T80-480q0-68 20-128t56-110L26-848l43-43L876-84l-43 43Zm-353-99q55 0 104-15.5t91-43.5L498-376l-77 78-165-166 45-45 120 120 32-32-254-254q-28 42-43.5 91T140-480q0 145 97.5 242.5T480-140Zm324-102-43-43q28-42 43.5-91T820-480q0-145-97.5-242.5T480-820q-55 0-104 15.5T285-761l-43-43q50-36 110-56t128-20q84 0 157 31t127 85q54 54 85 127t31 157q0 68-20 128t-56 110ZM585-462l-46-45 119-119 46 45-119 119Zm-62-61Zm-86 86Z"/></svg>

        {{ _e("Deleted") }}
    </a>


    <div class="dropdown-menu">
        <button type="button" class="dropdown-item" onclick="mw.admin.content.restoreFromTrash('{{ $content->id }}')">

            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M440-320h80v-166l64 62 56-56-160-160-160 160 56 56 64-62v166ZM280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520Zm-400 0v520-520Z"/></svg>
            {{ _e("Restore from trash") }}
        </button>
        <button type="button" class="dropdown-item"  onclick="mw.admin.content.deleteForever('{{ $content->id }}')">
            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m376-300 104-104 104 104 56-56-104-104 104-104-56-56-104 104-104-104-56 56 104 104-104 104 56 56Zm-96 180q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520Zm-400 0v520-520Z"/></svg>
            {{ _e("Delete forever") }}
        </button>
    </div>
</div>
