terraform {
  required_providers {
    google = {
      source = "hashicorp/google"
      version = "4.52.0"
    }
  }
}

provider "google" {
  project = "inf1005"
  region  = "us-west1"
  zone    = "us-west1-b"
}

resource "google_compute_network" "vpc_network" {
  name = "terraform-network"
}

resource "google_compute_firewall" "allow_http" {
  name    = "allow-http-rule"
  network = google_compute_network.vpc_network.name
  allow {
    ports    = ["80"]
    protocol = "tcp"
  }
  source_ranges = ["0.0.0.0/0"]
  target_tags = ["allow-http"]
  priority    = 1000
}

resource "google_compute_firewall" "ssh" {
  name = "allow-ssh"
  allow {
    ports    = ["22"]
    protocol = "tcp"
  }
  direction     = "INGRESS"
  network       = google_compute_network.vpc_network.name
  priority      = 1000
  source_ranges = ["0.0.0.0/0"]
  target_tags   = ["allow-ssh"]
}

resource "google_compute_firewall" "allow_https" {
  name    = "allow-https-rule"
  network = google_compute_network.vpc_network.name
  allow {
    ports    = ["443"]
    protocol = "tcp"
  }
  source_ranges = ["0.0.0.0/0"]
  target_tags = ["allow-https"]
  priority    = 1000
}

resource "google_compute_address" "static" {
  name = "ipv4-address"
  network_tier = "STANDARD"
  region  = "us-west1"
}

resource "google_compute_instance" "vm_instance" {
  name         = "inf1005-lamp1"
  machine_type = "e2-micro"

  depends_on = [
    google_compute_address.static
  ]

  boot_disk {
    initialize_params {
      image = "ubuntu-os-cloud/ubuntu-2004-lts"
    }
  }

  network_interface {
    network = google_compute_network.vpc_network.name
    access_config {
        nat_ip = google_compute_address.static.address
        network_tier = "STANDARD"
    }
  }

  tags = ["allow-ssh", "allow-http", "allow-https"]
  metadata_startup_script = file("server.sh")
}


output "public_ip" {
  value = "${google_compute_instance.vm_instance.network_interface.0.access_config.0.nat_ip}"
}
