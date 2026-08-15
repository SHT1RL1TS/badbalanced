<?php
if(!isset($_SESSION['user_name'])) {
    Header('Location:home');
    exit;
}
try {
    $heroes = getAllHeroes($db);
    $patches = getAllPatches($db);
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">'.$e->getMessage().'</div>';
}
?>
<br>
<br>
<br>
<div class="editor-container">
    <div class="editor-header">
        <h1>⚔️ Редактор скиллов</h1>
        <button class="btn btn-primary" onclick="openSkillModal()">+ Добавить скилл</button>
    </div>
    <div class="editor-table-wrapper">
        <table class="editor-table" id="skillsTable">
            <thead><tr><th>ID</th><th>Иконка</th><th>Название</th><th>Герой</th><th>Патч</th><th>Действия</th></tr></thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div id="skillModal" class="bb-modal">
    <div class="bb-modal-content">
        <span class="bb-modal-close" onclick="closeSkillModal()">&times;</span>
        <h2 id="modalTitle">Добавить скилл</h2>
        <form id="skillForm">
            <input type="hidden" id="skill_id" name="id_skill">
            <div class="form-group"><label>Название</label><input type="text" id="skill_name" name="name_skill" required></div>
            <div class="form-group"><label>Описание</label><textarea id="skill_desc" name="description_skill" rows="3"></textarea></div>
            <div class="form-group"><label>URL изображения</label><input type="text" id="skill_img" name="image_url_skill"></div>
            <div class="form-group"><label>Герой</label>
                <select id="skill_hero" name="id_hero"><option value="">-- Не выбран --</option><?php foreach ($heroes as $h): ?><option value="<?= $h['id_hero'] ?>"><?= htmlspecialchars($h['name_hero']) ?></option><?php endforeach; ?></select>
            </div>
            <div class="form-group"><label>Патч</label>
                <select id="skill_patch" name="id"><option value="">-- Не выбран --</option><?php foreach ($patches as $p): ?><option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option><?php endforeach; ?></select>
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
</div>

<script>
  const SKILLS_API = '<?= $basePath . 'api/skills_api.php' ?>';
  function loadSkills() {
      $.get(SKILLS_API, {action:'list'}, function(r){
          if(r.success){
              let h='';
              r.data.forEach(s=>{
                  h+=`<tr><td>${s.id_skill}</td>
                  <td><img src="/cd-project/butbalanced/src/abilities/${s.image_url_skill||''}" style="width:40px;height:40px;border-radius:4px;" onerror="this.style.display='none'"></td>
                  <td>${s.name_skill}</td><td>${s.hero_name||'-'}</td><td>${s.patch_name||'-'}</td>
                  <td><button class="btn btn-sm btn-outline" onclick="editSkill(${s.id_skill})">Edit</button>
                  <button class="btn btn-sm btn-danger" onclick="deleteSkill(${s.id_skill})">Del</button></td></tr>`;
              });
              $('#skillsTable tbody').html(h);
          }
      });
  }
  function openSkillModal(){$('#skillForm')[0].reset();$('#skill_id').val('');$('#modalTitle').text('Добавить скилл');$('#skillModal').show();}
  function closeSkillModal(){$('#skillModal').hide();}
  function editSkill(id){
      $.get(SKILLS_API,{action:'get',id:id},function(r){
          if(r.success&&r.data){const s=r.data;$('#skill_id').val(s.id_skill);$('#skill_name').val(s.name_skill);$('#skill_desc').val(s.description_skill);$('#skill_img').val(s.image_url_skill);$('#skill_hero').val(s.id_hero);$('#skill_patch').val(s.id);$('#modalTitle').text('Редактировать скилл');$('#skillModal').show();}
      });
  }
  function deleteSkill(id){if(!confirm('Удалить скилл?'))return;$.ajax({url:SKILLS_API+'?id='+id,type:'DELETE',success:function(r){if(r.success)loadSkills();else alert(r.message);}});}
  $('#skillForm').on('submit',function(e){e.preventDefault();
      const id=$('#skill_id').val();
      $.post(SKILLS_API,{action:id?'update':'create',id_skill:id||undefined,name_skill:$('#skill_name').val(),description_skill:$('#skill_desc').val(),image_url_skill:$('#skill_img').val(),id_hero:$('#skill_hero').val()||null,id:$('#skill_patch').val()||null},function(r){
          if(r.success){closeSkillModal();loadSkills();}else alert(r.message);
      });
  });
  $(document).ready(loadSkills);
</script>
