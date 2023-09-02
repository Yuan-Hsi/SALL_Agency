// navbar
/* Set the width of the side navigation to 250px */
function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
}

/* Set the width of the side navigation to 0 */
function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

const model = tf.sequential();

// First layer must have an input shape defined.
model.add(tf.layers.dense({ units: 32, inputShape: [50] }));
// Afterwards, TF.js does automatic shape inference.
model.add(tf.layers.dense({ units: 4 }));

// Inspect the inferred shape of the model's output, which equals
// `[null, 4]`. The 1st dimension is the undetermined batch dimension; the
// 2nd is the output size of the model's last layer.
console.log(JSON.stringify(model.outputs[0].shape));
