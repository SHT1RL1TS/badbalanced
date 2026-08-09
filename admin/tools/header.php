<?php
  $currentPage = isset($route) ? $route : 'home';
?>
<div class="U7wdCoQycRPyErCfTw2sv _2RDnzkp-PsG6VBmzbEr4Wc">
  <div class="RsnZktJ6AqSXT0LNzVzby">
    <div class="_3WYkc7ouYrp_o9fb9euwrA">
      <div class="_3WYK_cont">
        <?php if(isset($_SESSION['user_name'])): ?>
          <a class="_15Uwp7E3cvI8g0xSa_K9WK <?= $currentPage === 'home' ? '_3ulNR3VlHLYvZ3PQlOXxdm' : '' ?>" href="<?=$baseUrl?>home">home</a>
          <a class="_15Uwp7E3cvI8g0xSa_K9WK <?= $currentPage === 'heroes' ? '_3ulNR3VlHLYvZ3PQlOXxdm' : '' ?>" href="<?=$baseUrl?>heroes">heroes</a>
          <!--<div class="patches-dropdown" id="patchesDropdown">
            <a class="_15Uwp7E3cvI8g0xSa_K9WK patches-btn <?= ($currentPage === 'patches' || $currentPage === 'patches/add' || $currentPage === 'patches/edit') ? '_3ulNR3VlHLYvZ3PQlOXxdm' : '' ?>" href="javascript:void(0)">
              PATCHES
            </a>
            <div class="patches-dropdown-content" id="patchesDropdownContent">
              <a href="<=$baseUrl?>patch/add">ADD</a>
              <a href="<=$baseUrl?>patch/edit">EDIT</a>
            </div>
          </div>-->
        <?php else: ?>
          <a class="_15Uwp7E3cvI8g0xSa_K9WK <?= $currentPage === 'home' ? '_3ulNR3VlHLYvZ3PQlOXxdm' : '' ?>" href="<?=$baseUrl?>../client/home">home</a>
        <?php endif ?>
      </div>
      <?php if(isset($_SESSION['user_name'])): ?>
        <a class="_15Uwp7E3cvI8g0xSa_K9WK <?= $currentPage === 'logout' ? '_3ulNR3VlHLYvZ3PQlOXxdm' : '' ?>" href="<?=$baseUrl?>logout">logout</a>
      <?php else: ?>
        <a class="_15Uwp7E3cvI8g0xSa_K9WK <?= $currentPage === 'login' ? '_3ulNR3VlHLYvZ3PQlOXxdm' : '' ?>" href="<?=$baseUrl?>login">login</a>
      <?php endif ?>
    </div>
    <div class="MM-C2Bi-pVHzw7EMKBDgZ">
      <?php if(isset($_SESSION['user_name'])): ?>
        <div class="vkYBh8y2GsZajPZsy64Rb"></div>
        <div class="vkYBh8y2GsZajPZsy64Rb"></div>
      <?php else: ?>
        <div class="vkYBh8y2GsZajPZsy64Rb"></div>
      <?php endif ?>
      <div class="vkYBh8y2GsZajPZsy64Rb"></div>
    </div>
  </div>
</div>
