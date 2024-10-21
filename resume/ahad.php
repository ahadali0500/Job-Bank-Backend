<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Grid System</title>
<style>
/* Grid container styles */
.grid-container {
  display: grid;
  grid-template-columns: repeat(3, 1fr); /* Three equal-width columns */
  gap: 20px; /* Gap between grid items */
}

/* Grid item styles */
.grid-item {
  padding: 20px;
  background-color: #f2f2f2;
  border: 1px solid #ddd;
}
</style>
</head>
<body>

<div class="grid-container">
  <div class="grid-item">1</div>
  <div class="grid-item">2</div>
  <div class="grid-item">3</div>
  <div class="grid-item">4</div>
  <div class="grid-item">5</div>
  <div class="grid-item">6</div>
</div>

</body>
</html>
