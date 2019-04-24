namespace WebApi.DTO
{
    public class CustomerBasicInformation
    {
        public int Id;
        public string Email { get; set; }
        public string FullName { get; set; }
        public PhoneNumberAndTypesInformation phoneNumbers { get; set; }
    }
}