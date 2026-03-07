<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/Graph.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\SchemaOrg\Graph
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-946df2fda4722c62375af08cbcfc91ce1cbebb1a9e007354abe29968cb878e5d-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\SchemaOrg\\Graph',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../spatie/schema-org/src/Graph.php',
      ),
    ),
    'namespace' => 'Spatie\\SchemaOrg',
    'name' => 'Spatie\\SchemaOrg\\Graph',
    'shortName' => 'Graph',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @method self|ThreeDimensionalModel threeDimensionalModel(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AMRadioChannel aMRadioChannel(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|APIReference aPIReference(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AboutPage aboutPage(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AcceptAction acceptAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Accommodation accommodation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AccountingService accountingService(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AchieveAction achieveAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Action action(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ActionAccessSpecification actionAccessSpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ActionStatusType actionStatusType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ActivateAction activateAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AddAction addAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AdministrativeArea administrativeArea(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AdultEntertainment adultEntertainment(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AdultOrientedEnumeration adultOrientedEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AdvertiserContentArticle advertiserContentArticle(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AggregateOffer aggregateOffer(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AggregateRating aggregateRating(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AgreeAction agreeAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Airline airline(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Airport airport(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AlignmentObject alignmentObject(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AllocateAction allocateAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AmpStory ampStory(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AmusementPark amusementPark(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AnalysisNewsArticle analysisNewsArticle(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AnatomicalStructure anatomicalStructure(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AnatomicalSystem anatomicalSystem(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AnimalShelter animalShelter(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Answer answer(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Apartment apartment(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ApartmentComplex apartmentComplex(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AppendAction appendAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ApplyAction applyAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ApprovedIndication approvedIndication(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Aquarium aquarium(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ArchiveComponent archiveComponent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ArchiveOrganization archiveOrganization(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ArriveAction arriveAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ArtGallery artGallery(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Artery artery(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Article article(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AskAction askAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AskPublicNewsArticle askPublicNewsArticle(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AssessAction assessAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AssignAction assignAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Atlas atlas(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Attorney attorney(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Audience audience(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AudioObject audioObject(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AudioObjectSnapshot audioObjectSnapshot(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Audiobook audiobook(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AuthorizeAction authorizeAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AutoBodyShop autoBodyShop(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AutoDealer autoDealer(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AutoPartsStore autoPartsStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AutoRental autoRental(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AutoRepair autoRepair(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AutoWash autoWash(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AutomatedTeller automatedTeller(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|AutomotiveBusiness automotiveBusiness(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BackgroundNewsArticle backgroundNewsArticle(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Bakery bakery(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BankAccount bankAccount(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BankOrCreditUnion bankOrCreditUnion(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BarOrPub barOrPub(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Barcode barcode(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Beach beach(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BeautySalon beautySalon(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BedAndBreakfast bedAndBreakfast(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BedDetails bedDetails(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BedType bedType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BefriendAction befriendAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BikeStore bikeStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BioChemEntity bioChemEntity(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Blog blog(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BlogPosting blogPosting(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BloodTest bloodTest(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BoardingPolicyType boardingPolicyType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BoatReservation boatReservation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BoatTerminal boatTerminal(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BoatTrip boatTrip(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BodyMeasurementTypeEnumeration bodyMeasurementTypeEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BodyOfWater bodyOfWater(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Bone bone(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Book book(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BookFormatType bookFormatType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BookSeries bookSeries(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BookStore bookStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BookmarkAction bookmarkAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BorrowAction borrowAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BowlingAlley bowlingAlley(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BrainStructure brainStructure(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Brand brand(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BreadcrumbList breadcrumbList(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Brewery brewery(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Bridge bridge(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BroadcastChannel broadcastChannel(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BroadcastEvent broadcastEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BroadcastFrequencySpecification broadcastFrequencySpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BroadcastService broadcastService(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BrokerageAccount brokerageAccount(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BuddhistTemple buddhistTemple(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BusOrCoach busOrCoach(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BusReservation busReservation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BusStation busStation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BusStop busStop(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BusTrip busTrip(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BusinessAudience businessAudience(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BusinessEntityType businessEntityType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BusinessEvent businessEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BusinessFunction businessFunction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|BuyAction buyAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CDCPMDRecord cDCPMDRecord(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CableOrSatelliteService cableOrSatelliteService(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CafeOrCoffeeShop cafeOrCoffeeShop(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Campground campground(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CampingPitch campingPitch(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Canal canal(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CancelAction cancelAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Car car(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CarUsageType carUsageType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Casino casino(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CategoryCode categoryCode(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CategoryCodeSet categoryCodeSet(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CatholicChurch catholicChurch(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Cemetery cemetery(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Certification certification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CertificationStatusEnumeration certificationStatusEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Chapter chapter(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CheckAction checkAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CheckInAction checkInAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CheckOutAction checkOutAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CheckoutPage checkoutPage(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ChemicalSubstance chemicalSubstance(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ChildCare childCare(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ChildrensEvent childrensEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ChooseAction chooseAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Church church(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|City city(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CityHall cityHall(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CivicStructure civicStructure(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Claim claim(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ClaimReview claimReview(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Clip clip(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ClothingStore clothingStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Code code(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Collection collection(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CollectionPage collectionPage(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CollegeOrUniversity collegeOrUniversity(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ComedyClub comedyClub(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ComedyEvent comedyEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ComicCoverArt comicCoverArt(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ComicIssue comicIssue(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ComicSeries comicSeries(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ComicStory comicStory(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Comment comment(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CommentAction commentAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CommunicateAction communicateAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CompleteDataFeed completeDataFeed(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CompoundPriceSpecification compoundPriceSpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ComputerLanguage computerLanguage(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ComputerStore computerStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ConfirmAction confirmAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Consortium consortium(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ConstraintNode constraintNode(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ConsumeAction consumeAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ContactPage contactPage(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ContactPoint contactPoint(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ContactPointOption contactPointOption(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Continent continent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ControlAction controlAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ConvenienceStore convenienceStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Conversation conversation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CookAction cookAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Cooperative cooperative(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Corporation corporation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CorrectionComment correctionComment(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Country country(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Course course(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CourseInstance courseInstance(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Courthouse courthouse(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CoverArt coverArt(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CovidTestingFacility covidTestingFacility(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CreateAction createAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CreativeWork creativeWork(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CreativeWorkSeason creativeWorkSeason(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CreativeWorkSeries creativeWorkSeries(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CreditCard creditCard(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Crematorium crematorium(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CriticReview criticReview(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CssSelectorType cssSelectorType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|CurrencyConversionService currencyConversionService(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DDxElement dDxElement(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DanceEvent danceEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DanceGroup danceGroup(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DataCatalog dataCatalog(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DataDownload dataDownload(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DataFeed dataFeed(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DataFeedItem dataFeedItem(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Dataset dataset(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DatedMoneySpecification datedMoneySpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DayOfWeek dayOfWeek(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DaySpa daySpa(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DeactivateAction deactivateAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DefenceEstablishment defenceEstablishment(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DefinedRegion definedRegion(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DefinedTerm definedTerm(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DefinedTermSet definedTermSet(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DeleteAction deleteAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DeliveryChargeSpecification deliveryChargeSpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DeliveryEvent deliveryEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DeliveryMethod deliveryMethod(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DeliveryTimeSettings deliveryTimeSettings(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Demand demand(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Dentist dentist(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DepartAction departAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DepartmentStore departmentStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DepositAccount depositAccount(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DiagnosticLab diagnosticLab(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DiagnosticProcedure diagnosticProcedure(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Diet diet(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DietarySupplement dietarySupplement(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DigitalDocument digitalDocument(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DigitalDocumentPermission digitalDocumentPermission(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DigitalDocumentPermissionType digitalDocumentPermissionType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DigitalPlatformEnumeration digitalPlatformEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DisagreeAction disagreeAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DiscoverAction discoverAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DiscussionForumPosting discussionForumPosting(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DislikeAction dislikeAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Distance distance(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Distillery distillery(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DonateAction donateAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DoseSchedule doseSchedule(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DownloadAction downloadAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DrawAction drawAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Drawing drawing(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DrinkAction drinkAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DriveWheelConfigurationValue driveWheelConfigurationValue(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Drug drug(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DrugClass drugClass(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DrugCost drugCost(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DrugCostCategory drugCostCategory(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DrugLegalStatus drugLegalStatus(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DrugPregnancyCategory drugPregnancyCategory(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DrugPrescriptionStatus drugPrescriptionStatus(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DrugStrength drugStrength(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|DryCleaningOrLaundry dryCleaningOrLaundry(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Duration duration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EUEnergyEfficiencyEnumeration eUEnergyEfficiencyEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EatAction eatAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EducationEvent educationEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EducationalAudience educationalAudience(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EducationalOccupationalCredential educationalOccupationalCredential(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EducationalOccupationalProgram educationalOccupationalProgram(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EducationalOrganization educationalOrganization(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Electrician electrician(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ElectronicsStore electronicsStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ElementarySchool elementarySchool(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EmailMessage emailMessage(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Embassy embassy(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EmergencyService emergencyService(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EmployeeRole employeeRole(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EmployerAggregateRating employerAggregateRating(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EmployerReview employerReview(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EmploymentAgency employmentAgency(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EndorseAction endorseAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EndorsementRating endorsementRating(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Energy energy(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EnergyConsumptionDetails energyConsumptionDetails(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EnergyEfficiencyEnumeration energyEfficiencyEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EnergyStarEnergyEfficiencyEnumeration energyStarEnergyEfficiencyEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EngineSpecification engineSpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EntertainmentBusiness entertainmentBusiness(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EntryPoint entryPoint(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Enumeration enumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Episode episode(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Event event(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EventAttendanceModeEnumeration eventAttendanceModeEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EventReservation eventReservation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EventSeries eventSeries(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EventStatusType eventStatusType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|EventVenue eventVenue(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ExchangeRateSpecification exchangeRateSpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ExerciseAction exerciseAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ExerciseGym exerciseGym(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ExercisePlan exercisePlan(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ExhibitionEvent exhibitionEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FAQPage fAQPage(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FMRadioChannel fMRadioChannel(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FastFoodRestaurant fastFoodRestaurant(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Festival festival(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FilmAction filmAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FinancialIncentive financialIncentive(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FinancialProduct financialProduct(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FinancialService financialService(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FindAction findAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FireStation fireStation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Flight flight(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FlightReservation flightReservation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FloorPlan floorPlan(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Florist florist(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FollowAction followAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FoodEstablishment foodEstablishment(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FoodEstablishmentReservation foodEstablishmentReservation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FoodEvent foodEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FoodService foodService(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FulfillmentTypeEnumeration fulfillmentTypeEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FundingAgency fundingAgency(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FundingScheme fundingScheme(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|FurnitureStore furnitureStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Game game(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GameAvailabilityEnumeration gameAvailabilityEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GamePlayMode gamePlayMode(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GameServer gameServer(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GameServerStatus gameServerStatus(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GardenStore gardenStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GasStation gasStation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GatedResidenceCommunity gatedResidenceCommunity(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GenderType genderType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Gene gene(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GeneralContractor generalContractor(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GeoCircle geoCircle(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GeoCoordinates geoCoordinates(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GeoShape geoShape(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GeospatialGeometry geospatialGeometry(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GiveAction giveAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GolfCourse golfCourse(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GovernmentBenefitsType governmentBenefitsType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GovernmentBuilding governmentBuilding(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GovernmentOffice governmentOffice(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GovernmentOrganization governmentOrganization(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GovernmentPermit governmentPermit(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GovernmentService governmentService(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Grant grant(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|GroceryStore groceryStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Guide guide(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HVACBusiness hVACBusiness(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Hackathon hackathon(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HairSalon hairSalon(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HardwareStore hardwareStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HealthAndBeautyBusiness healthAndBeautyBusiness(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HealthAspectEnumeration healthAspectEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HealthClub healthClub(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HealthInsurancePlan healthInsurancePlan(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HealthPlanCostSharingSpecification healthPlanCostSharingSpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HealthPlanFormulary healthPlanFormulary(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HealthPlanNetwork healthPlanNetwork(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HealthTopicContent healthTopicContent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HighSchool highSchool(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HinduTemple hinduTemple(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HobbyShop hobbyShop(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HomeAndConstructionBusiness homeAndConstructionBusiness(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HomeGoodsStore homeGoodsStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Hospital hospital(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Hostel hostel(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Hotel hotel(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HotelRoom hotelRoom(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|House house(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HousePainter housePainter(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HowTo howTo(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HowToDirection howToDirection(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HowToItem howToItem(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HowToSection howToSection(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HowToStep howToStep(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HowToSupply howToSupply(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HowToTip howToTip(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HowToTool howToTool(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HyperToc hyperToc(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|HyperTocEntry hyperTocEntry(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|IPTCDigitalSourceEnumeration iPTCDigitalSourceEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|IceCreamShop iceCreamShop(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|IgnoreAction ignoreAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ImageGallery imageGallery(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ImageObject imageObject(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ImageObjectSnapshot imageObjectSnapshot(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ImagingTest imagingTest(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|IncentiveQualifiedExpenseType incentiveQualifiedExpenseType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|IncentiveStatus incentiveStatus(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|IncentiveType incentiveType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|IndividualPhysician individualPhysician(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|IndividualProduct individualProduct(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|InfectiousAgentClass infectiousAgentClass(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|InfectiousDisease infectiousDisease(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|InformAction informAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|InsertAction insertAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|InstallAction installAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|InsuranceAgency insuranceAgency(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Intangible intangible(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|InteractAction interactAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|InteractionCounter interactionCounter(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|InternetCafe internetCafe(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|InvestmentFund investmentFund(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|InvestmentOrDeposit investmentOrDeposit(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|InviteAction inviteAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Invoice invoice(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ItemAvailability itemAvailability(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ItemList itemList(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ItemListOrderType itemListOrderType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ItemPage itemPage(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|JewelryStore jewelryStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|JobPosting jobPosting(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|JoinAction joinAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Joint joint(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LakeBodyOfWater lakeBodyOfWater(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Landform landform(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LandmarksOrHistoricalBuildings landmarksOrHistoricalBuildings(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Language language(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LearningResource learningResource(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LeaveAction leaveAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LegalForceStatus legalForceStatus(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LegalService legalService(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LegalValueLevel legalValueLevel(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Legislation legislation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LegislationObject legislationObject(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LegislativeBuilding legislativeBuilding(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LendAction lendAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Library library(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LibrarySystem librarySystem(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LifestyleModification lifestyleModification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Ligament ligament(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LikeAction likeAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LinkRole linkRole(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LiquorStore liquorStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ListItem listItem(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ListenAction listenAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LiteraryEvent literaryEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LiveBlogPosting liveBlogPosting(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LoanOrCredit loanOrCredit(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LocalBusiness localBusiness(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LocationFeatureSpecification locationFeatureSpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Locksmith locksmith(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LodgingBusiness lodgingBusiness(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LodgingReservation lodgingReservation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LoseAction loseAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|LymphaticVessel lymphaticVessel(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Manuscript manuscript(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Map map(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MapCategoryType mapCategoryType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MarryAction marryAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Mass mass(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MathSolver mathSolver(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MaximumDoseSchedule maximumDoseSchedule(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MeasurementMethodEnum measurementMethodEnum(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MeasurementTypeEnumeration measurementTypeEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MediaEnumeration mediaEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MediaGallery mediaGallery(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MediaManipulationRatingEnumeration mediaManipulationRatingEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MediaObject mediaObject(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MediaReview mediaReview(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MediaReviewItem mediaReviewItem(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MediaSubscription mediaSubscription(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalAudience medicalAudience(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalAudienceType medicalAudienceType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalBusiness medicalBusiness(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalCause medicalCause(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalClinic medicalClinic(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalCode medicalCode(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalCondition medicalCondition(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalConditionStage medicalConditionStage(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalContraindication medicalContraindication(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalDevice medicalDevice(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalDevicePurpose medicalDevicePurpose(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalEntity medicalEntity(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalEnumeration medicalEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalEvidenceLevel medicalEvidenceLevel(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalGuideline medicalGuideline(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalGuidelineContraindication medicalGuidelineContraindication(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalGuidelineRecommendation medicalGuidelineRecommendation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalImagingTechnique medicalImagingTechnique(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalIndication medicalIndication(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalIntangible medicalIntangible(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalObservationalStudy medicalObservationalStudy(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalObservationalStudyDesign medicalObservationalStudyDesign(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalOrganization medicalOrganization(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalProcedure medicalProcedure(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalProcedureType medicalProcedureType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalRiskCalculator medicalRiskCalculator(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalRiskEstimator medicalRiskEstimator(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalRiskFactor medicalRiskFactor(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalRiskScore medicalRiskScore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalScholarlyArticle medicalScholarlyArticle(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalSign medicalSign(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalSignOrSymptom medicalSignOrSymptom(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalSpecialty medicalSpecialty(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalStudy medicalStudy(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalStudyStatus medicalStudyStatus(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalSymptom medicalSymptom(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalTest medicalTest(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalTestPanel medicalTestPanel(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalTherapy medicalTherapy(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalTrial medicalTrial(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalTrialDesign medicalTrialDesign(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicalWebPage medicalWebPage(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MedicineSystem medicineSystem(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MeetingRoom meetingRoom(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MemberProgram memberProgram(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MemberProgramTier memberProgramTier(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MensClothingStore mensClothingStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Menu menu(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MenuItem menuItem(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MenuSection menuSection(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MerchantReturnEnumeration merchantReturnEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MerchantReturnPolicy merchantReturnPolicy(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MerchantReturnPolicySeasonalOverride merchantReturnPolicySeasonalOverride(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Message message(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MiddleSchool middleSchool(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MobileApplication mobileApplication(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MobilePhoneStore mobilePhoneStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MolecularEntity molecularEntity(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MonetaryAmount monetaryAmount(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MonetaryAmountDistribution monetaryAmountDistribution(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MonetaryGrant monetaryGrant(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MoneyTransfer moneyTransfer(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MortgageLoan mortgageLoan(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Mosque mosque(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Motel motel(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Motorcycle motorcycle(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MotorcycleDealer motorcycleDealer(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MotorcycleRepair motorcycleRepair(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MotorizedBicycle motorizedBicycle(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Mountain mountain(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MoveAction moveAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Movie movie(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MovieClip movieClip(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MovieRentalStore movieRentalStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MovieSeries movieSeries(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MovieTheater movieTheater(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MovingCompany movingCompany(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Muscle muscle(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Museum museum(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MusicAlbum musicAlbum(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MusicAlbumProductionType musicAlbumProductionType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MusicAlbumReleaseType musicAlbumReleaseType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MusicComposition musicComposition(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MusicEvent musicEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MusicGroup musicGroup(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MusicPlaylist musicPlaylist(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MusicRecording musicRecording(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MusicRelease musicRelease(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MusicReleaseFormatType musicReleaseFormatType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MusicStore musicStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MusicVenue musicVenue(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|MusicVideoObject musicVideoObject(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|NGO nGO(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|NLNonprofitType nLNonprofitType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|NailSalon nailSalon(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Nerve nerve(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|NewsArticle newsArticle(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|NewsMediaOrganization newsMediaOrganization(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Newspaper newspaper(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|NightClub nightClub(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|NonprofitType nonprofitType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Notary notary(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|NoteDigitalDocument noteDigitalDocument(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|NutritionInformation nutritionInformation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Observation observation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Occupation occupation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OccupationalExperienceRequirements occupationalExperienceRequirements(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OccupationalTherapy occupationalTherapy(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OceanBodyOfWater oceanBodyOfWater(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Offer offer(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OfferCatalog offerCatalog(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OfferForLease offerForLease(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OfferForPurchase offerForPurchase(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OfferItemCondition offerItemCondition(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OfferShippingDetails offerShippingDetails(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OfficeEquipmentStore officeEquipmentStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OnDemandEvent onDemandEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OnlineBusiness onlineBusiness(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OnlineMarketplace onlineMarketplace(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OnlineStore onlineStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OpeningHoursSpecification openingHoursSpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OpinionNewsArticle opinionNewsArticle(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Optician optician(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Order order(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OrderAction orderAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OrderItem orderItem(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OrderStatus orderStatus(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Organization organization(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OrganizationRole organizationRole(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OrganizeAction organizeAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OutletStore outletStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|OwnershipInfo ownershipInfo(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PaintAction paintAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Painting painting(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PalliativeProcedure palliativeProcedure(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ParcelDelivery parcelDelivery(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ParentAudience parentAudience(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Park park(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ParkingFacility parkingFacility(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PathologyTest pathologyTest(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Patient patient(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PawnShop pawnShop(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PayAction payAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PaymentCard paymentCard(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PaymentChargeSpecification paymentChargeSpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PaymentMethod paymentMethod(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PaymentMethodType paymentMethodType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PaymentService paymentService(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PaymentStatusType paymentStatusType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PeopleAudience peopleAudience(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PerformAction performAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PerformanceRole performanceRole(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PerformingArtsTheater performingArtsTheater(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PerformingGroup performingGroup(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Periodical periodical(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Permit permit(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Person person(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PetStore petStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Pharmacy pharmacy(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Photograph photograph(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PhotographAction photographAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PhysicalActivity physicalActivity(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PhysicalActivityCategory physicalActivityCategory(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PhysicalExam physicalExam(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PhysicalTherapy physicalTherapy(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Physician physician(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PhysiciansOffice physiciansOffice(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Place place(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PlaceOfWorship placeOfWorship(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PlanAction planAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Play play(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PlayAction playAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PlayGameAction playGameAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Playground playground(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Plumber plumber(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PodcastEpisode podcastEpisode(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PodcastSeason podcastSeason(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PodcastSeries podcastSeries(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PoliceStation policeStation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PoliticalParty politicalParty(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Pond pond(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PostOffice postOffice(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PostalAddress postalAddress(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PostalCodeRangeSpecification postalCodeRangeSpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Poster poster(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PreOrderAction preOrderAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PrependAction prependAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Preschool preschool(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PresentationDigitalDocument presentationDigitalDocument(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PreventionIndication preventionIndication(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PriceComponentTypeEnumeration priceComponentTypeEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PriceSpecification priceSpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PriceTypeEnumeration priceTypeEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Product product(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ProductCollection productCollection(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ProductGroup productGroup(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ProductModel productModel(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ProductReturnEnumeration productReturnEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ProductReturnPolicy productReturnPolicy(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ProfessionalService professionalService(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ProfilePage profilePage(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ProgramMembership programMembership(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Project project(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PronounceableText pronounceableText(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Property property(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PropertyValue propertyValue(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PropertyValueSpecification propertyValueSpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Protein protein(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PsychologicalTreatment psychologicalTreatment(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PublicSwimmingPool publicSwimmingPool(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PublicToilet publicToilet(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PublicationEvent publicationEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PublicationIssue publicationIssue(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PublicationVolume publicationVolume(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|PurchaseType purchaseType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|QAPage qAPage(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|QualitativeValue qualitativeValue(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|QuantitativeValue quantitativeValue(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|QuantitativeValueDistribution quantitativeValueDistribution(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Quantity quantity(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Question question(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Quiz quiz(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Quotation quotation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|QuoteAction quoteAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RVPark rVPark(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RadiationTherapy radiationTherapy(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RadioBroadcastService radioBroadcastService(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RadioChannel radioChannel(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RadioClip radioClip(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RadioEpisode radioEpisode(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RadioSeason radioSeason(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RadioSeries radioSeries(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RadioStation radioStation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Rating rating(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ReactAction reactAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ReadAction readAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RealEstateAgent realEstateAgent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RealEstateListing realEstateListing(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ReceiveAction receiveAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Recipe recipe(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Recommendation recommendation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RecommendedDoseSchedule recommendedDoseSchedule(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RecyclingCenter recyclingCenter(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RefundTypeEnumeration refundTypeEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RegisterAction registerAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RejectAction rejectAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RentAction rentAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RentalCarReservation rentalCarReservation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RepaymentSpecification repaymentSpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ReplaceAction replaceAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ReplyAction replyAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Report report(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ReportageNewsArticle reportageNewsArticle(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ReportedDoseSchedule reportedDoseSchedule(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ResearchOrganization researchOrganization(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ResearchProject researchProject(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Researcher researcher(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Reservation reservation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ReservationPackage reservationPackage(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ReservationStatusType reservationStatusType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ReserveAction reserveAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Reservoir reservoir(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Residence residence(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Resort resort(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Restaurant restaurant(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RestrictedDiet restrictedDiet(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ResumeAction resumeAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ReturnAction returnAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ReturnFeesEnumeration returnFeesEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ReturnLabelSourceEnumeration returnLabelSourceEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ReturnMethodEnumeration returnMethodEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Review review(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ReviewAction reviewAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ReviewNewsArticle reviewNewsArticle(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RiverBodyOfWater riverBodyOfWater(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Role role(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RoofingContractor roofingContractor(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Room room(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RsvpAction rsvpAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|RsvpResponseType rsvpResponseType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SaleEvent saleEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SatiricalArticle satiricalArticle(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Schedule schedule(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ScheduleAction scheduleAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ScholarlyArticle scholarlyArticle(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|School school(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SchoolDistrict schoolDistrict(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ScreeningEvent screeningEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Sculpture sculpture(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SeaBodyOfWater seaBodyOfWater(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SearchAction searchAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SearchRescueOrganization searchRescueOrganization(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SearchResultsPage searchResultsPage(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Season season(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Seat seat(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SeekToAction seekToAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SelfStorage selfStorage(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SellAction sellAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SendAction sendAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Series series(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Service service(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ServiceChannel serviceChannel(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ServicePeriod servicePeriod(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ShareAction shareAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SheetMusic sheetMusic(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ShippingConditions shippingConditions(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ShippingDeliveryTime shippingDeliveryTime(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ShippingRateSettings shippingRateSettings(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ShippingService shippingService(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ShoeStore shoeStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ShoppingCenter shoppingCenter(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ShortStory shortStory(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SingleFamilyResidence singleFamilyResidence(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SiteNavigationElement siteNavigationElement(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SizeGroupEnumeration sizeGroupEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SizeSpecification sizeSpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SizeSystemEnumeration sizeSystemEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SkiResort skiResort(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SocialEvent socialEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SocialMediaPosting socialMediaPosting(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SoftwareApplication softwareApplication(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SoftwareSourceCode softwareSourceCode(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SolveMathAction solveMathAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SomeProducts someProducts(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SpeakableSpecification speakableSpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SpecialAnnouncement specialAnnouncement(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Specialty specialty(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SportingGoodsStore sportingGoodsStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SportsActivityLocation sportsActivityLocation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SportsClub sportsClub(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SportsEvent sportsEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SportsOrganization sportsOrganization(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SportsTeam sportsTeam(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SpreadsheetDigitalDocument spreadsheetDigitalDocument(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|StadiumOrArena stadiumOrArena(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|State state(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Statement statement(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|StatisticalPopulation statisticalPopulation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|StatisticalVariable statisticalVariable(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|StatusEnumeration statusEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SteeringPositionValue steeringPositionValue(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Store store(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|StructuredValue structuredValue(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|StupidType stupidType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SubscribeAction subscribeAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Substance substance(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SubwayStation subwayStation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Suite suite(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SuperficialAnatomy superficialAnatomy(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SurgicalProcedure surgicalProcedure(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|SuspendAction suspendAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Syllabus syllabus(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Synagogue synagogue(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TVClip tVClip(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TVEpisode tVEpisode(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TVSeason tVSeason(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TVSeries tVSeries(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Table table(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TakeAction takeAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TattooParlor tattooParlor(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Taxi taxi(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TaxiReservation taxiReservation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TaxiService taxiService(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TaxiStand taxiStand(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Taxon taxon(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TechArticle techArticle(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TelevisionChannel televisionChannel(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TelevisionStation televisionStation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TennisComplex tennisComplex(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TextDigitalDocument textDigitalDocument(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TextObject textObject(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TheaterEvent theaterEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TheaterGroup theaterGroup(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TherapeuticProcedure therapeuticProcedure(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Thesis thesis(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Thing thing(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Ticket ticket(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TieAction tieAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TierBenefitEnumeration tierBenefitEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TipAction tipAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TireShop tireShop(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TouristAttraction touristAttraction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TouristDestination touristDestination(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TouristInformationCenter touristInformationCenter(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TouristTrip touristTrip(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ToyStore toyStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TrackAction trackAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TradeAction tradeAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TrainReservation trainReservation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TrainStation trainStation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TrainTrip trainTrip(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TransferAction transferAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TravelAction travelAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TravelAgency travelAgency(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TreatmentIndication treatmentIndication(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Trip trip(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|TypeAndQuantityNode typeAndQuantityNode(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|UKNonprofitType uKNonprofitType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|USNonprofitType uSNonprofitType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|UnRegisterAction unRegisterAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|UnitPriceSpecification unitPriceSpecification(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|UpdateAction updateAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|UseAction useAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|UserBlocks userBlocks(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|UserCheckins userCheckins(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|UserComments userComments(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|UserDownloads userDownloads(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|UserInteraction userInteraction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|UserLikes userLikes(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|UserPageVisits userPageVisits(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|UserPlays userPlays(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|UserPlusOnes userPlusOnes(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|UserReview userReview(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|UserTweets userTweets(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|VacationRental vacationRental(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Vehicle vehicle(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Vein vein(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Vessel vessel(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|VeterinaryCare veterinaryCare(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|VideoGallery videoGallery(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|VideoGame videoGame(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|VideoGameClip videoGameClip(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|VideoGameSeries videoGameSeries(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|VideoObject videoObject(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|VideoObjectSnapshot videoObjectSnapshot(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|ViewAction viewAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|VirtualLocation virtualLocation(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|VisualArtsEvent visualArtsEvent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|VisualArtwork visualArtwork(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|VitalSign vitalSign(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Volcano volcano(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|VoteAction voteAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WPAdBlock wPAdBlock(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WPFooter wPFooter(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WPHeader wPHeader(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WPSideBar wPSideBar(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WantAction wantAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WarrantyPromise warrantyPromise(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WarrantyScope warrantyScope(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WatchAction watchAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Waterfall waterfall(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WearAction wearAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WearableMeasurementTypeEnumeration wearableMeasurementTypeEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WearableSizeGroupEnumeration wearableSizeGroupEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WearableSizeSystemEnumeration wearableSizeSystemEnumeration(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WebAPI webAPI(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WebApplication webApplication(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WebContent webContent(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WebPage webPage(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WebPageElement webPageElement(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WebSite webSite(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WholesaleStore wholesaleStore(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WinAction winAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Winery winery(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WorkBasedProgram workBasedProgram(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WorkersUnion workersUnion(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|WriteAction writeAction(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|XPathType xPathType(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 * @method self|Zoo zoo(string|\\Closure|null $identifier = null, \\Closure|null $callback = null)
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 930,
    'endLine' => 1264,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\SchemaOrg\\Type',
      1 => 'ArrayAccess',
      2 => 'JsonSerializable',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'IDENTIFIER_DEFAULT' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'name' => 'IDENTIFIER_DEFAULT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'default\'',
          'attributes' => 
          array (
            'startLine' => 932,
            'endLine' => 932,
            'startTokenPos' => 78,
            'startFilePos' => 108152,
            'endTokenPos' => 78,
            'endFilePos' => 108160,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 932,
        'endLine' => 932,
        'startColumn' => 5,
        'endColumn' => 48,
      ),
    ),
    'immediateProperties' => 
    array (
      'nodes' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'name' => 'nodes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 935,
            'endLine' => 935,
            'startTokenPos' => 89,
            'startFilePos' => 108210,
            'endTokenPos' => 90,
            'endFilePos' => 108211,
          ),
        ),
        'docComment' => '/** @var Type[] */',
        'attributes' => 
        array (
        ),
        'startLine' => 935,
        'endLine' => 935,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'hidden' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'name' => 'hidden',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 938,
            'endLine' => 938,
            'startTokenPos' => 101,
            'startFilePos' => 108261,
            'endTokenPos' => 102,
            'endFilePos' => 108262,
          ),
        ),
        'docComment' => '/** @var array */',
        'attributes' => 
        array (
        ),
        'startLine' => 938,
        'endLine' => 938,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'context' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'name' => 'context',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/** @var string|null */',
        'attributes' => 
        array (
        ),
        'startLine' => 941,
        'endLine' => 941,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'nonce' => 
      array (
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'name' => 'nonce',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'\'',
          'attributes' => 
          array (
            'startLine' => 944,
            'endLine' => 944,
            'startTokenPos' => 120,
            'startFilePos' => 108365,
            'endTokenPos' => 120,
            'endFilePos' => 108366,
          ),
        ),
        'docComment' => '/** @var string */',
        'attributes' => 
        array (
        ),
        'startLine' => 944,
        'endLine' => 944,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'context' => 
          array (
            'name' => 'context',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 946,
                'endLine' => 946,
                'startTokenPos' => 139,
                'startFilePos' => 108431,
                'endTokenPos' => 139,
                'endFilePos' => 108434,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 946,
            'endLine' => 946,
            'startColumn' => 33,
            'endColumn' => 65,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 946,
        'endLine' => 949,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      '__call' => 
      array (
        'name' => '__call',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 971,
            'endLine' => 971,
            'startColumn' => 28,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'arguments' => 
          array (
            'name' => 'arguments',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 971,
            'endLine' => 971,
            'startColumn' => 44,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * This overloads all \\Spatie\\SchemaOrg\\Schema construction methods.
 * You can call them the same like on the \\Spatie\\SchemaOrg\\Schema class.
 * But you can also use the extended signatures.
 *
 * Graph::organisation(): Organisation
 * Graph::organisation(\'spatie\'): Organisation
 * Graph::organisation(function(Organisation $organisation, Graph $graph) {}): Graph
 * Graph::organisation(\'spatie\', function(Organisation $organisation, Graph $graph) {}): Graph
 *
 * @see \\Spatie\\SchemaOrg\\Schema
 *
 * @param string $method
 * @param array $arguments
 *
 * @return $this|Type
 *
 * @throws \\ReflectionException
 * @throws \\BadMethodCallException
 */',
        'startLine' => 971,
        'endLine' => 1006,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'if' => 
      array (
        'name' => 'if',
        'parameters' => 
        array (
          'condition' => 
          array (
            'name' => 'condition',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1008,
            'endLine' => 1008,
            'startColumn' => 24,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1008,
            'endLine' => 1008,
            'startColumn' => 41,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1008,
        'endLine' => 1015,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'add' => 
      array (
        'name' => 'add',
        'parameters' => 
        array (
          'schema' => 
          array (
            'name' => 'schema',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Spatie\\SchemaOrg\\Type',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1017,
            'endLine' => 1017,
            'startColumn' => 25,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'identifier' => 
          array (
            'name' => 'identifier',
            'default' => 
            array (
              'code' => 'self::IDENTIFIER_DEFAULT',
              'attributes' => 
              array (
                'startLine' => 1017,
                'endLine' => 1017,
                'startTokenPos' => 506,
                'startFilePos' => 110769,
                'endTokenPos' => 508,
                'endFilePos' => 110792,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1017,
            'endLine' => 1017,
            'startColumn' => 39,
            'endColumn' => 83,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1017,
        'endLine' => 1026,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'has' => 
      array (
        'name' => 'has',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1028,
            'endLine' => 1028,
            'startColumn' => 25,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'identifier' => 
          array (
            'name' => 'identifier',
            'default' => 
            array (
              'code' => 'self::IDENTIFIER_DEFAULT',
              'attributes' => 
              array (
                'startLine' => 1028,
                'endLine' => 1028,
                'startTokenPos' => 595,
                'startFilePos' => 111155,
                'endTokenPos' => 597,
                'endFilePos' => 111178,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1028,
            'endLine' => 1028,
            'startColumn' => 39,
            'endColumn' => 83,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1028,
        'endLine' => 1031,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'set' => 
      array (
        'name' => 'set',
        'parameters' => 
        array (
          'schema' => 
          array (
            'name' => 'schema',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Spatie\\SchemaOrg\\Type',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1033,
            'endLine' => 1033,
            'startColumn' => 25,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'identifier' => 
          array (
            'name' => 'identifier',
            'default' => 
            array (
              'code' => 'self::IDENTIFIER_DEFAULT',
              'attributes' => 
              array (
                'startLine' => 1033,
                'endLine' => 1033,
                'startTokenPos' => 652,
                'startFilePos' => 111367,
                'endTokenPos' => 654,
                'endFilePos' => 111390,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1033,
            'endLine' => 1033,
            'startColumn' => 39,
            'endColumn' => 83,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1033,
        'endLine' => 1038,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'get' => 
      array (
        'name' => 'get',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1040,
            'endLine' => 1040,
            'startColumn' => 25,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'identifier' => 
          array (
            'name' => 'identifier',
            'default' => 
            array (
              'code' => 'self::IDENTIFIER_DEFAULT',
              'attributes' => 
              array (
                'startLine' => 1040,
                'endLine' => 1040,
                'startTokenPos' => 701,
                'startFilePos' => 111553,
                'endTokenPos' => 703,
                'endFilePos' => 111576,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1040,
            'endLine' => 1040,
            'startColumn' => 39,
            'endColumn' => 83,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Spatie\\SchemaOrg\\Type',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1040,
        'endLine' => 1047,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'getOrCreate' => 
      array (
        'name' => 'getOrCreate',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1049,
            'endLine' => 1049,
            'startColumn' => 33,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'identifier' => 
          array (
            'name' => 'identifier',
            'default' => 
            array (
              'code' => 'self::IDENTIFIER_DEFAULT',
              'attributes' => 
              array (
                'startLine' => 1049,
                'endLine' => 1049,
                'startTokenPos' => 782,
                'startFilePos' => 111910,
                'endTokenPos' => 784,
                'endFilePos' => 111933,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1049,
            'endLine' => 1049,
            'startColumn' => 47,
            'endColumn' => 91,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Spatie\\SchemaOrg\\Type',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1049,
        'endLine' => 1060,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'hide' => 
      array (
        'name' => 'hide',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1062,
            'endLine' => 1062,
            'startColumn' => 26,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'identifier' => 
          array (
            'name' => 'identifier',
            'default' => 
            array (
              'code' => 'self::IDENTIFIER_DEFAULT',
              'attributes' => 
              array (
                'startLine' => 1062,
                'endLine' => 1062,
                'startTokenPos' => 901,
                'startFilePos' => 112350,
                'endTokenPos' => 903,
                'endFilePos' => 112373,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1062,
            'endLine' => 1062,
            'startColumn' => 40,
            'endColumn' => 85,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1062,
        'endLine' => 1086,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'show' => 
      array (
        'name' => 'show',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1088,
            'endLine' => 1088,
            'startColumn' => 26,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'identifier' => 
          array (
            'name' => 'identifier',
            'default' => 
            array (
              'code' => 'self::IDENTIFIER_DEFAULT',
              'attributes' => 
              array (
                'startLine' => 1088,
                'endLine' => 1088,
                'startTokenPos' => 1052,
                'startFilePos' => 112998,
                'endTokenPos' => 1054,
                'endFilePos' => 113021,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1088,
            'endLine' => 1088,
            'startColumn' => 40,
            'endColumn' => 85,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1088,
        'endLine' => 1135,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'setNonce' => 
      array (
        'name' => 'setNonce',
        'parameters' => 
        array (
          'nonce' => 
          array (
            'name' => 'nonce',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1137,
            'endLine' => 1137,
            'startColumn' => 30,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1137,
        'endLine' => 1142,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'toArray' => 
      array (
        'name' => 'toArray',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1144,
        'endLine' => 1172,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'serializeNode' => 
      array (
        'name' => 'serializeNode',
        'parameters' => 
        array (
          'node' => 
          array (
            'name' => 'node',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1174,
            'endLine' => 1174,
            'startColumn' => 38,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1174,
        'endLine' => 1186,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'getNodes' => 
      array (
        'name' => 'getNodes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1188,
        'endLine' => 1191,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'getContext' => 
      array (
        'name' => 'getContext',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'array',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1193,
        'endLine' => 1196,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'nonceAttr' => 
      array (
        'name' => 'nonceAttr',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1198,
        'endLine' => 1207,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'toScript' => 
      array (
        'name' => 'toScript',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1209,
        'endLine' => 1212,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'jsonSerialize' => 
      array (
        'name' => 'jsonSerialize',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1214,
        'endLine' => 1217,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      '__toString' => 
      array (
        'name' => '__toString',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1219,
        'endLine' => 1222,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'getTypeAndIdentifier' => 
      array (
        'name' => 'getTypeAndIdentifier',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1224,
            'endLine' => 1224,
            'startColumn' => 45,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1224,
        'endLine' => 1231,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'offsetExists' => 
      array (
        'name' => 'offsetExists',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1233,
            'endLine' => 1233,
            'startColumn' => 34,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1233,
        'endLine' => 1238,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'offsetGet' => 
      array (
        'name' => 'offsetGet',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1240,
            'endLine' => 1240,
            'startColumn' => 31,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1240,
        'endLine' => 1245,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'offsetSet' => 
      array (
        'name' => 'offsetSet',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1247,
            'endLine' => 1247,
            'startColumn' => 31,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1247,
            'endLine' => 1247,
            'startColumn' => 40,
            'endColumn' => 45,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1247,
        'endLine' => 1256,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
      'offsetUnset' => 
      array (
        'name' => 'offsetUnset',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1258,
            'endLine' => 1258,
            'startColumn' => 33,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1258,
        'endLine' => 1263,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\SchemaOrg',
        'declaringClassName' => 'Spatie\\SchemaOrg\\Graph',
        'implementingClassName' => 'Spatie\\SchemaOrg\\Graph',
        'currentClassName' => 'Spatie\\SchemaOrg\\Graph',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));