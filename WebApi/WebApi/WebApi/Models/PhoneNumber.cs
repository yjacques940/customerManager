namespace WebApi.Models
{
    public class PhoneNumber : BaseModel
    {
        public string Phonenumber { get; set; }
        public string Extension { get; set; }
        public int IdPhoneType { get; set; }
    }
}
