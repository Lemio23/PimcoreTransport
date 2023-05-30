function clock()
{
	var today = new Date();
	
	var hour = today.getHours();
	var minutes = today.getMinutes();
	var seconds = today.getSeconds();
	
	document.getElementById("clock").innerHTML = hour+":"+minutes+":"+seconds;
	
	setTimeout("clock()",1000);
}

function documentOnload()
{
	let shippingDocuments = document.getElementById("shippingDocuments");
	let chosenFile = document.getElementById("chosedFile");
	let fileName = document.getElementById("fileName");
	let documentContainer = document.getElementById("documentContainer");
	let docError = document.getElementById("docError");
	let fileDisplay = document.getElementById("fileDisplay");

	const fileHandler = (file, name, type) =>
	{
		docError.innerText = "yo";
		if (name.split(".")[1] !== "jpg" && name.split(".")[1] !== "png" && name.split(".")[1] !== "doc" && name.split(".")[1] !== "docx" && name.split(".")[1] !== "pdf")
		{
			docError.innerText = "Niedozwolone rozszerzenie, wybierz plik z jednym z rozszerzeń: .jpg, .png, .doc, .docx, .pdf";
			return false;
		}
	
		docError.innerText = "";
		let reader = new FileReader();
		reader.readAsDataURL(file);
		reader.onloadend = () =>
		{
			let container = document.createElement("figure");
			let doc = document.createElement("doc");
			doc.src = reader.result;
			container.appendChild(doc);
			container.innerHTML += `<figcaption>${name}
			</figcaption>`;
			fileDisplay.appendChild(container);
		};
	};

	shippingDocuments.addEventListener("change", () =>
	{
		fileDisplay.innerHTML = "";
		Array.from(shippingDocuments.files).forEach((file) =>
		{
			fileHandler(file, file.name, file.type);
		}
		);
	}
	);

	documentContainer.addEventListener(
		"dragenter",
		(e) => 
		{
			e.preventDefault();
			e.stopPropagation();
			documentContainer.classList.add("active");
		},
		false
	);

	documentContainer.addEventListener(
		"dragleave",
		(e) =>
		{
			e.preventDefault();
			e.stopPropagation();
			documentContainer.classList.remove("active");
		},
		false
	);

	documentContainer.addEventListener(
		"dragover",
		(e) => 
		{
			e.preventDefault();
			e.stopPropagation();
			documentContainer.classList.add("active");
		},
		false
	);

	documentContainer.addEventListener(
		"drop",
		(e) =>
		{
			e.preventDefault();
			e.stopPropagation();
			documentContainer.classList.remove("active");
			let draggedData = e.dataTransfer;
			let files = draggedData.files;
			fileDisplay.innerHTML = "";
			Array.from(files).forEach((file) => 
			{
				fileHandler(file, file.name, file.type);
			});
		},
		false
	);

	window.onload = () =>
	{
		docError.innerText = "";
	};
}

function onloadFunc()
{
	clock();
	documentOnload();	
	addCargo();
}



let inputCount = 0;
	
function addCargo()
{
	let cargoCon = document.getElementById("cargoContainer");
	inputCount++;
	
	let newDiv = document.createElement('div');
	
	newDiv.innerText = "Ładunek " + inputCount.toString() + " (nazwa, cieżar w kg, typ) \n";
	
	let inputNazwa = document.createElement('input');
	inputNazwa.type = "text";
	var nazwaName = "cargoName" + inputCount.toString();
	inputNazwa.name = nazwaName;
	newDiv.appendChild(inputNazwa);
	
	let inputCiezar = document.createElement('input');
	inputCiezar.type = "number";
	var ciezarName = "cargoCiezar" + inputCount.toString();
	inputCiezar.name = ciezarName;
	newDiv.appendChild(inputCiezar);
	
	let inputTyp = document.createElement('select');
	let option1 = document.createElement('option');
	option1.innerText = "Zwykły";
	option1.value = "Zwykly";
	
	let option2 = document.createElement('option');
	option2.value = "Niebezpieczny";
	option2.innerText = "Niebezpieczny";
	
	inputTyp.appendChild(option1);
	inputTyp.appendChild(option2);
	
	var inputTypId = "cargoType" + inputCount.toString();
	inputTyp.id = inputTypId;
	inputTyp.name = inputTypId;
	
	newDiv.appendChild(inputTyp);
	
	cargoCon.appendChild(newDiv);
	
	
}