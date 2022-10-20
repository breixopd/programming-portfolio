import hashlib, os

#dir = "C:/Users/Forensic-Security/Downloads/Hash Values/"

def hashf(hash):
    dir = str(input("Enter full dir using /\n> "))
    os.system('cls')
    h = hashlib.new(hash)
    for filename in os.listdir(dir):
        h.update(open(dir + filename, 'rb').read())
        print(f"Name: {filename}\nHash: {h.hexdigest()}\n-----")

try:
    type = int(input("Enter hash you want to check\n1 = MD5\n2 = SHA1\n3 = SHA256\n> "))
    if type == 1:
        hashf('md5')
    elif type == 2: 
        hashf('sha1')
    elif type == 3:
        hashf('sha256')
    else:
        print("Enter a valid option!")
except:
    print("Enter a valid option!")
