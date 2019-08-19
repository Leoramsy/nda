<template>
    <form>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" />
        </div>
        <div class="form-group">
            <label for="name">Surname</label>
            <input type="text" class="form-control" name="surname" id="surname" />
        </div>        
        <div class="form-group">
            <label for="mobile">Mobile Number</label>
            <input type="text" class="form-control" name="email" id="mobile" />
        </div>
        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" class="form-control" name="email" id="email" />
        </div>
        <div class="form-group">
            <label for="province">Province</label>
            <input type="text" class="form-control" name="province" id="province" />
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</template>

<script>
export default {
  
}
</script>