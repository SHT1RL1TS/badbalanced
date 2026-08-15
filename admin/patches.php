<?php
if(!isset($_SESSION['user_name'])) {
    Header('Location:home');
    exit;
}
try {
    $patches = getAllPatches($db);
    $heroes = getAllHeroes($db);
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">'.$e->getMessage().'</div>';
}
?>
<br>
<br>
<br>
<div class="editor-container">
    <div class="editor-header">
        <h1>📝 Патчи</h1>
        <button class="btn btn-primary" onclick="openPatchModal()">+ Создать патч</button>
    </div>
    <div class="editor-table-wrapper">
        <table class="editor-table" id="patchesTable">
            <thead><tr><th>ID</th><th>Название</th><th>Major</th><th>Описание</th><th>Действия</th></tr></thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div id="patchModal" class="bb-modal">
    <div class="bb-modal-content" style="max-width: 600px;">
        <span class="bb-modal-close" onclick="closePatchModal()">&times;</span>
        <h2 id="patchModalTitle">Создать патч</h2>
        <form id="patchForm">
            <input type="hidden" id="patch_id" name="id">
            <div class="form-group"><label>Название патча</label><input type="text" id="patch_name" name="name" required></div>
            <div class="form-group"><label>Описание</label><textarea id="patch_desc" name="description" rows="3"></textarea></div>
            <div class="form-group"><label>URL изображения</label><input type="text" id="patch_img" name="patch_img_url"></div>
            <div class="form-group"><label><input type="checkbox" id="patch_major" name="is_major"> Major патч</label></div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
</div>

<script>
  const PATCHES_API = '<?= $baseUrl . 'api/patches_api.php'?>';
  function loadPatches() {
      $.get(PATCHES_API, {action:'list'}, function(r){
          if(r.success){
              let h='';
              r.data.forEach(p=>{
                  h+=`<tr><td>${p.id}</td><td>${p.name}</td><td>${p.is_major?'🔥':'-'}</td><td>${p.description||''}</td>
                  <td><button class="btn btn-sm btn-outline" onclick="editPatch(${p.id})">Edit</button>
                  <button class="btn btn-sm btn-danger" onclick="deletePatch(${p.id})">Del</button></td></tr>`;
              });
              $('#patchesTable tbody').html(h);
          }
      });
  }
  function openPatchModal(){$('#patchForm')[0].reset();$('#patch_id').val('');$('#patchModalTitle').text('Создать патч');$('#patchModal').show();}
  function closePatchModal(){$('#patchModal').hide();}
  function editPatch(id){
      $.get(PATCHES_API,{action:'get',id:id},function(r){
          if(r.success&&r.data){const p=r.data;$('#patch_id').val(p.id);$('#patch_name').val(p.name);$('#patch_desc').val(p.description);$('#patch_img').val(p.patch_img_url);$('#patch_major').prop('checked',p.is_major);$('#patchModalTitle').text('Редактировать патч');$('#patchModal').show();}
      });
  }
  function deletePatch(id){if(!confirm('Удалить патч?'))return;$.ajax({url:PATCHES_API+'?id='+id,type:'DELETE',success:function(r){if(r.success)loadPatches();else alert(r.message);}});}
  $('#patchForm').on('submit',function(e){e.preventDefault();
      const id=$('#patch_id').val();
      $.post(PATCHES_API,{action:id?'update':'create',id:id||undefined,name:$('#patch_name').val(),description:$('#patch_desc').val(),patch_img_url:$('#patch_img').val(),is_major:$('#patch_major').is(':checked')},function(r){
          if(r.success){closePatchModal();loadPatches();}else alert(r.message);
      });
  });
  $(document).ready(loadPatches);
</script>
