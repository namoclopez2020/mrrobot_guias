<?php
	$page_title = 'Agregar usuarios';
	require_once('includes/load.php');
	// Checkin What level user has permission to view this page
	page_require_level(2);
	$id_sucursal = $_SESSION['id_sucursal'];
	if($id_sucursal == '4'){
		$groups = find_all('user_groups');
		$all_sucursales=find_all('sucursales');
	}else{
		$groups = find_groups_by_level();
		$all_sucursales=find_sucursales_by_level();
	}
	
?>
<?php
  if(isset($_POST['add_user'])){

   $req_fields = array('full-name','username','password','level' );
   validate_fields($req_fields);

   if(empty($errors)){
	   $sucursal   = (int)$db->escape($_POST['sucursal']);
           $name   = remove_junk($db->escape($_POST['full-name']));
       $username   = remove_junk($db->escape($_POST['username']));
       $password   = remove_junk($db->escape($_POST['password']));
       $user_level = (int)$db->escape($_POST['level']);
       $password = sha1($password);
        $query = "INSERT INTO users (";
        $query .="name,username,password,user_level,status,id_sucursal";
        $query .=") VALUES (";
        $query .=" '{$name}', '{$username}', '{$password}', '{$user_level}','1','{$sucursal}'";
        $query .=")";
        if($db->query($query)){
          //sucess
          $session->msg('s'," Cuenta de usuario ha sido creada");
          redirect('add_user.php', false);
        } else {
          //failed
          $session->msg('d',' No se pudo crear la cuenta.');
          redirect('add_user.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('add_user.php',false);
   }
 }
?>
<head>
  <?php include ("./layouts/header.php");?>
    
  </head>
<body>
    
    <header>
    <?php include ("./layouts/nav.php");?>
  </header>
  <body> 
    <div  class="container-fluid" style="padding-top: 60px">
  <?php echo display_msg($msg); ?>
  <div class="row">
    <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Agregar usuario</span>
       </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
          <form method="post" action="add_user.php">
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" class="form-control" name="full-name" placeholder="Nombre completo" required>
            </div>
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" class="form-control" name="username" placeholder="Nombre de usuario">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" style="color:black" name ="password"  placeholder="Contraseña">
            </div>
			<div class="form-group">
				<label for="sucursal">Sucursal</label>
                    <select class="form-control" name="sucursal" >
                      <option value="">Selecciona una sucursal</option>
                    <?php  foreach ($all_sucursales as $suc): ?>
                      <option value="<?php echo $suc['id'] ?>">
                        <?php echo $suc['nombre_sucursal'] ?></option>
                    <?php endforeach; ?>
                    </select>
					</div> 
            <div class="form-group">
              <label for="level">Rol de usuario</label>
                <select class="form-control" name="level">
                  <?php foreach ($groups as $group ):?>
                   <option value="<?php echo $group['group_level'];?>"><?php echo ucwords($group['group_name']);?></option>
                <?php endforeach;?>
                </select>
            </div>
            <div class="form-group clearfix">
              <button type="submit" name="add_user" class="btn btn-primary">Guardar</button>
            </div>
        </form>
        </div>

      </div>

    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
