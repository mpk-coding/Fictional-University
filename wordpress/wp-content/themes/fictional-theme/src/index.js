import "../css/style.scss";

// Our modules / classes
import MobileMenu from "./modules/MobileMenu";
import HeroSlider from "./modules/HeroSlider";
import GoogleMap from "./modules/GoogleMap";
import Search from "./modules/Search";
import Notes from "./modules/myNotes";
import Likes from "./modules/Likes";

window.addEventListener("DOMContentLoaded", (event) => {
	// Instantiate a new object using our modules/classes
	var mobileMenu = new MobileMenu();
	var heroSlider = new HeroSlider();
	const googleMap = new GoogleMap();
	const search = new Search();
	const notes = new Notes();
	const likes = new Likes();
});
