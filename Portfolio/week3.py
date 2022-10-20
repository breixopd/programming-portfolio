# greeting
# name = input("What is your name? ")
# print(f"Hello, {name}") if name != "" else print("Hello, stranger!")


# password
def password_input(password):
    BAD_PASSWORDS = ['password', 'letmein', 'sesame', 'hello', 'justinbieber']
    if password in BAD_PASSWORDS or len(password) < 8 or password.isalpha():
        print("Password needs to be between 8 and 12 characters with a number or symbol")
        print("Password can't be: password, letmein, sesame, hello, justinbieber")
        password_input(input("Set a password: "))
    else:
        if len(password) > 12:
            print("Password needs to be between 8 and 12 characters")
            password_input(input("Set a password: "))
        else:
            password2 = input("Confirm password: ")
            if password == password2:
                print("Password set")
            else:
                print("Passwords do not match")
                password_input(input("Set a password: "))


password_input(input("Set a password: "))


# times table
def times_table(number=-7):
    if number > 12:
        print("Number too high")
    # if number negative print table backwards
    elif number < 0:
        for i in range(12, 0, -1):
            print(f"{number} x {i} = {number * i}")
    else:
        for i in range(1, 13):
            print(f"{number} x {i} = {number * i}")


# times_table(int(input("Enter a number: ")))
