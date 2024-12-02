import torch
import torchvision.transforms as transforms
from torchvision.models import resnet18
from PIL import Image
import os
from torch.nn.functional import cosine_similarity
import numpy as np

# Define the image folder and file names
image_folder = "AI/images"
image1_path = os.path.join(image_folder, "image1.jfif")
image2_path = os.path.join(image_folder, "image2.jfif")

# Load the pretrained model
model = resnet18(pretrained=True)
model.eval()  
model = torch.nn.Sequential(*list(model.children())[:-1])  # Remove the classification layer

# Define the image transformations
transform = transforms.Compose([
    transforms.Resize((224, 224)),
    transforms.ToTensor(),
    transforms.Normalize(mean=[0.485, 0.456, 0.406], std=[0.229, 0.224, 0.225]),
])

# Function to extract ResNet features
def extract_resnet_features(image_path):
    image = Image.open(image_path).convert("RGB")  
    image = transform(image).unsqueeze(0)  
    with torch.no_grad():
        features = model(image).squeeze()  
    return features

# Function to extract color histogram features
def extract_color_features(image_path):
    image = Image.open(image_path).convert("RGB") 
    hist_r = np.histogram(image.getdata(0), bins=256, range=(0, 256), density=True)[0]
    hist_g = np.histogram(image.getdata(1), bins=256, range=(0, 256), density=True)[0]
    hist_b = np.histogram(image.getdata(2), bins=256, range=(0, 256), density=True)[0]
    return np.concatenate([hist_r, hist_g, hist_b])

def normalize_vector(vec):
    return vec / np.linalg.norm(vec)

# Extract features for both images
features1_resnet = extract_resnet_features(image1_path)
features2_resnet = extract_resnet_features(image2_path)

features1_color = normalize_vector(extract_color_features(image1_path))
features2_color = normalize_vector(extract_color_features(image2_path))

# Compute cosine similarity for ResNet features
similarity_resnet = cosine_similarity(features1_resnet.unsqueeze(0), features2_resnet.unsqueeze(0)).item()

# Compute cosine similarity for color histograms
similarity_color = np.dot(features1_color, features2_color)

# Define thresholds 
resnet_threshold = 0.75  # Shape similarity threshold
color_threshold = 0.90 

# Determine result
if similarity_resnet > resnet_threshold and similarity_color > color_threshold:
    print("Return Processed successfully[same products]")
elif similarity_resnet > resnet_threshold and similarity_color <= color_threshold:
    print("Return Refused[Products are same but different colours]")
else:
    print("Return Refused [Different Products]")